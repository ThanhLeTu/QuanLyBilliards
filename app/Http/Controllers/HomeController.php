<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Phương thức tính toán thống kê bàn
    private function calculateTableStats()
    {
        $totalTables = Table::count();
        $activeTables = Table::where('status', 'playing')->count();
        $usageRate = ($totalTables > 0) ? ($activeTables / $totalTables) * 100 : 0;

        // Tính tổng doanh thu từ bảng invoices
        $totalRevenue = Invoice::sum('total_payment');
        
        return [
            'totalTables' => $totalTables,
            'activeTables' => $activeTables,
            'usageRate' => number_format($usageRate, 2),
            'totalRevenue' => number_format($totalRevenue, 0)
        ];
    }

    public function index()
    {
        $stats = $this->calculateTableStats();
        $tables = Table::all();
        $availableTables = Table::where('status', 'available')->get();

        // Lấy dữ liệu doanh thu theo ngày (hoặc theo tuần/tháng)
        $revenueData = $this->getRevenueData();

        return view('home', compact('tables', 'availableTables') + $stats + $revenueData);
    }

    // API trả về thống kê doanh thu (dành cho việc vẽ biểu đồ)
    public function getRevenueData()
    {
        // Lấy doanh thu theo ngày, tuần, tháng, ...
        $dailyRevenue = Invoice::selectRaw('DATE(created_at) as date, sum(total_payment) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueDates = $dailyRevenue->pluck('date');
        $revenueValues = $dailyRevenue->pluck('total');

        return [
            'revenueDates' => $revenueDates,
            'revenueValues' => $revenueValues
        ];
    }

    // API lấy thông tin thống kê bàn
    public function getTableStats()
    {
        $stats = $this->calculateTableStats();
        $availableTables = Table::where('status', 'available')->get();

        return response()->json([
            'totalTables' => $stats['totalTables'],
            'activeTables' => $stats['activeTables'],
            'usageRate' => $stats['usageRate'],
            'availableTables' => $availableTables
        ]);
    }
}
