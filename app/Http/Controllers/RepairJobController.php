<?php

namespace App\Http\Controllers;

use App\Models\RepairJob;
use App\Models\RepairStep;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RepairJobController extends Controller
{
    public function index(): View
    {
        $repairJobs = RepairJob::with(['reception.customer', 'reception.device', 'technician'])
            ->latest()
            ->paginate(15);

        return view('repair-jobs.index', compact('repairJobs'));
    }

    public function create()
    {
        // Thường repair job được tạo tự động hoặc tạo từ màn hình Reception
        // Controller này tập trung vào update và show
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(RepairJob $repairJob): View
    {
        $repairJob->load(['reception.customer', 'reception.device', 'technician', 'steps.user']);
        $technicians = User::all(); // Nên lọc theo role technician thực tế

        return view('repair-jobs.show', compact('repairJob', 'technicians'));
    }

    public function edit(RepairJob $repairJob): View
    {
        $repairJob->load(['reception.customer', 'reception.device', 'technician']);
        $technicians = User::all();

        return view('repair-jobs.edit', compact('repairJob', 'technicians'));
    }

    public function update(Request $request, RepairJob $repairJob): RedirectResponse
    {
        $data = $request->validate([
            'technician_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:pending,diagnosed,quoted,approved,repairing,waiting_parts,completed,cancelled'],
            'diagnosis' => ['nullable', 'string'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $originalStatus = $repairJob->status;
        $repairJob->update($data);

        // Ghi lại step nếu status thay đổi (không sync sang reception vì enum khác nhau)
        if ($data['status'] !== $originalStatus) {
            $repairJob->steps()->create([
                'user_id' => auth()->id(),
                'step_type' => 'status_change',
                'title' => 'Cập nhật trạng thái',
                'content' => "Chuyển trạng thái từ '{$originalStatus}' sang '{$data['status']}'",
            ]);
        }

        return redirect()->route('repair-jobs.show', $repairJob)
            ->with('success', 'Đã cập nhật công việc sửa chữa.');
    }

    public function destroy(RepairJob $repairJob): RedirectResponse
    {
        $repairJob->delete();

        return redirect()->route('repair-jobs.index')
            ->with('success', 'Đã xóa công việc sửa chữa.');
    }

    // Custom action: add step
    public function addStep(Request $request, RepairJob $repairJob): RedirectResponse
    {
        $data = $request->validate([
            'step_type' => ['required', 'in:diagnosis,quote,approval,repair_note,part_used,completion'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data['user_id'] = auth()->id();
        
        $repairJob->steps()->create($data);

        // Update repair job cost if part_used
        if ($data['step_type'] === 'part_used' && !empty($data['cost'])) {
            $repairJob->increment('final_cost', $data['cost']);
        }

        return back()->with('success', 'Đã thêm nhật ký sửa chữa.');
    }
}
