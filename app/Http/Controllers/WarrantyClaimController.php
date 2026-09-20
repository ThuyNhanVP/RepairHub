<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\WarrantyClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WarrantyClaimController extends Controller
{
    public function index(Request $request): View
    {
        $query = WarrantyClaim::with(['warranty.customer', 'warranty.device', 'user']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($query) use ($search): void {
                $query->where('claim_code', 'like', "%{$search}%")
                    ->orWhereHas('warranty', function ($warranty) use ($search): void {
                        $warranty->where('warranty_code', 'like', "%{$search}%")
                            ->orWhereHas('customer', fn ($customer) => $customer
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%"));
                    });
            });
        }

        if ($request->filled('status') && $request->string('status')->toString() !== 'all') {
            $query->where('status', $request->string('status')->toString());
        }

        $claims = $query->latest('received_at')->paginate(15)->withQueryString();

        return view('warranty-claims.index', compact('claims'));
    }

    public function create(): View
    {
        $warranties = Warranty::with(['customer', 'device'])
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('warranty-claims.create', compact('warranties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $warranty = Warranty::findOrFail($data['warranty_id']);

        abort_if($warranty->isExpired() || $warranty->status !== 'active', 422, 'Phiếu bảo hành đã hết hạn hoặc không còn hiệu lực.');

        $data['claim_code'] = $data['claim_code'] ?? $this->generateClaimCode();
        $data['status'] = $data['status'] ?? 'received';
        $data['user_id'] = auth()->id();
        $data['received_at'] = now();

        $claim = WarrantyClaim::create($data);

        return redirect()->route('warranty-claims.show', $claim)
            ->with('success', 'Đã tiếp nhận yêu cầu bảo hành.');
    }

    public function show(WarrantyClaim $warrantyClaim): View
    {
        $warrantyClaim->load(['warranty.customer', 'warranty.device', 'warranty.repairJob', 'user']);

        return view('warranty-claims.show', compact('warrantyClaim'));
    }

    public function edit(WarrantyClaim $warrantyClaim): View
    {
        return view('warranty-claims.edit', compact('warrantyClaim'));
    }

    public function update(Request $request, WarrantyClaim $warrantyClaim): RedirectResponse
    {
        $data = $this->validatedData($request, $warrantyClaim);
        $data['resolved_at'] = in_array($data['status'], ['completed', 'rejected', 'cancelled'], true)
            ? ($warrantyClaim->resolved_at ?? now())
            : null;

        $warrantyClaim->update($data);

        return redirect()->route('warranty-claims.show', $warrantyClaim)
            ->with('success', 'Đã cập nhật yêu cầu bảo hành.');
    }

    public function destroy(WarrantyClaim $warrantyClaim): RedirectResponse
    {
        $warrantyClaim->delete();

        return redirect()->route('warranty-claims.index')
            ->with('success', 'Đã xóa yêu cầu bảo hành.');
    }

    private function validatedData(Request $request, ?WarrantyClaim $warrantyClaim = null): array
    {
        $uniqueCode = 'unique:warranty_claims,claim_code';
        if ($warrantyClaim !== null) {
            $uniqueCode .= ','.$warrantyClaim->id;
        }

        return $request->validate([
            'warranty_id' => [$warrantyClaim === null ? 'required' : 'sometimes', 'exists:warranties,id'],
            'claim_code' => ['nullable', 'string', 'max:50', $uniqueCode],
            'status' => [$warrantyClaim === null ? 'nullable' : 'required', 'in:received,diagnosing,approved,rejected,completed,cancelled'],
            'issue' => ['required', 'string'],
            'resolution' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function generateClaimCode(): string
    {
        do {
            $code = 'CL-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (WarrantyClaim::where('claim_code', $code)->exists());

        return $code;
    }
}
