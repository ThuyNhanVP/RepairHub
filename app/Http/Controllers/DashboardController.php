<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Reception;
use App\Models\RepairJob;
use App\Models\Warranty;
use App\Models\WarrantyClaim;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $summary = [
            'totalReceptions' => Reception::count(),
            'activeRepairJobs' => RepairJob::whereNotIn('status', ['completed', 'cancelled'])->count(),
            'completedThisMonth' => RepairJob::where('status', 'completed')->whereBetween('updated_at', [$monthStart, $monthEnd])->count(),
            'monthlyRevenue' => RepairJob::where('status', 'completed')->whereBetween('updated_at', [$monthStart, $monthEnd])->sum('final_cost'),
            'activeWarranties' => Warranty::where('status', 'active')->whereDate('end_date', '>=', today())->count(),
            'openWarrantyClaims' => WarrantyClaim::whereNotIn('status', ['completed', 'rejected', 'cancelled'])->count(),
            'lowStockParts' => Part::whereColumn('stock_qty', '<=', 'min_stock_qty')->count(),
        ];

        $repairStatusLabels = [
            'pending' => 'Chờ xử lý',
            'diagnosed' => 'Đã chẩn đoán',
            'quoted' => 'Đã báo giá',
            'approved' => 'Đã duyệt',
            'repairing' => 'Đang sửa',
            'waiting_parts' => 'Chờ linh kiện',
            'completed' => 'Hoàn tất',
            'cancelled' => 'Đã hủy',
        ];
        $repairStatusCounts = RepairJob::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentReceptions = Reception::with(['customer', 'device'])->latest('received_at')->limit(5)->get();
        $lowStockPartList = Part::whereColumn('stock_qty', '<=', 'min_stock_qty')
            ->orderBy('stock_qty')
            ->limit(5)
            ->get(['id', 'name', 'sku', 'stock_qty', 'min_stock_qty', 'unit']);

        return view('dashboard', compact(
            'summary',
            'repairStatusLabels',
            'repairStatusCounts',
            'recentReceptions',
            'lowStockPartList',
            'monthStart',
            'monthEnd',
        ));
    }
}
