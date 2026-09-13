<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use App\Models\Customer;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceptionController extends Controller
{
    public function index(): View
    {
        $receptions = Reception::with(['customer', 'device', 'user'])
            ->latest()
            ->paginate(15);

        return view('receptions.index', compact('receptions'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();

        return view('receptions.create', compact('customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:devices,serial_number'],
            'imei' => ['nullable', 'string', 'max:20', 'unique:devices,imei'],
            'device_type' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'device_notes' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $device = Device::create([
            'customer_id' => $data['customer_id'],
            'brand' => $data['brand'] ?? null,
            'model' => $data['model'] ?? null,
            'serial_number' => $data['serial_number'] ?? null,
            'imei' => $data['imei'] ?? null,
            'device_type' => $data['device_type'] ?? null,
            'color' => $data['color'] ?? null,
            'notes' => $data['device_notes'] ?? null,
        ]);

        $reception = Reception::create([
            'customer_id' => $data['customer_id'],
            'device_id' => $device->id,
            'user_id' => auth()->id(),
            'status' => 'received',
            'description' => $data['description'] ?? null,
            'notes' => $data['notes'] ?? null,
            'received_at' => now(),
        ]);

        return redirect()->route('receptions.show', $reception)
            ->with('success', 'Phiếu tiếp nhận đã được tạo thành công.');
    }

    public function show(Reception $reception): View
    {
        $reception->load(['customer', 'device', 'user']);

        return view('receptions.show', compact('reception'));
    }

    public function edit(Reception $reception): View
    {
        $reception->load(['customer', 'device']);

        return view('receptions.edit', compact('reception'));
    }

    public function update(Request $request, Reception $reception): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:received,diagnosing,quoted,waiting_approval,repairing,waiting_parts,completed,delivered,cancelled'],
            'diagnosis' => ['nullable', 'string'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
            'estimated_completion_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'delivered_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $reception->update($data);

        return redirect()->route('receptions.show', $reception)
            ->with('success', 'Phiếu tiếp nhận đã được cập nhật.');
    }

    public function destroy(Reception $reception): RedirectResponse
    {
        $reception->delete();

        return redirect()->route('receptions.index')
            ->with('success', 'Phiếu tiếp nhận đã được xóa.');
    }
}
