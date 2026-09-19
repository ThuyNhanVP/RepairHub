<?php

namespace App\Http\Controllers;

use App\Models\RepairJob;
use App\Models\Warranty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Warranty::with(['customer', 'device', 'repairJob']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($query) use ($search): void {
                $query->where('warranty_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($customer) => $customer
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%"))
                    ->orWhereHas('device', fn ($device) => $device
                        ->where('serial_number', 'like', "%{$search}%")
                        ->orWhere('imei', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status') && $request->string('status')->toString() !== 'all') {
            $query->where('status', $request->string('status')->toString());
        }

        $warranties = $query->latest()->paginate(15)->withQueryString();

        return view('warranties.index', compact('warranties'));
    }

    public function create(): View
    {
        $repairJobs = RepairJob::with(['reception.customer', 'reception.device'])
            ->where('status', 'completed')
            ->whereDoesntHave('warranty')
            ->latest()
            ->get();

        return view('warranties.create', compact('repairJobs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $repairJob = RepairJob::with('reception')->findOrFail($data['repair_job_id']);

        abort_if($repairJob->status !== 'completed' || $repairJob->warranty()->exists(), 422, 'Công việc chưa đủ điều kiện tạo bảo hành.');

        $data['customer_id'] = $repairJob->reception->customer_id;
        $data['device_id'] = $repairJob->reception->device_id;
        $data['warranty_code'] = $data['warranty_code'] ?? $this->generateWarrantyCode();
        $data['end_date'] = Carbon::parse($data['start_date'])
            ->addMonthsNoOverflow($data['duration_months'])
            ->toDateString();

        $warranty = Warranty::create($data);

        return redirect()->route('warranties.show', $warranty)
            ->with('success', 'Đã tạo phiếu bảo hành.');
    }

    public function show(Warranty $warranty): View
    {
        $warranty->load(['customer', 'device', 'repairJob']);

        return view('warranties.show', compact('warranty'));
    }

    public function edit(Warranty $warranty): View
    {
        return view('warranties.edit', compact('warranty'));
    }

    public function update(Request $request, Warranty $warranty): RedirectResponse
    {
        $data = $this->validatedData($request, $warranty);
        $data['end_date'] = Carbon::parse($data['start_date'])
            ->addMonthsNoOverflow($data['duration_months'])
            ->toDateString();

        $warranty->update($data);

        return redirect()->route('warranties.show', $warranty)
            ->with('success', 'Đã cập nhật phiếu bảo hành.');
    }

    public function destroy(Warranty $warranty): RedirectResponse
    {
        $warranty->delete();

        return redirect()->route('warranties.index')
            ->with('success', 'Đã xóa phiếu bảo hành.');
    }

    private function validatedData(Request $request, ?Warranty $warranty = null): array
    {
        $uniqueCode = 'unique:warranties,warranty_code';
        if ($warranty !== null) {
            $uniqueCode .= ','.$warranty->id;
        }

        return $request->validate([
            'repair_job_id' => [$warranty === null ? 'required' : 'sometimes', 'exists:repair_jobs,id'],
            'warranty_code' => ['nullable', 'string', 'max:50', $uniqueCode],
            'start_date' => ['required', 'date'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'status' => ['required', 'in:active,expired,void'],
            'terms' => ['nullable', 'string'],
        ]);
    }

    private function generateWarrantyCode(): string
    {
        do {
            $code = 'WH-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (Warranty::where('warranty_code', $code)->exists());

        return $code;
    }
}
