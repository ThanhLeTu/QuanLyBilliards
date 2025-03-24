<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    // Phương thức riêng để tính toán thống kê bàn
    private function calculateTableStats()
    {
        $totalTables = Table::count();
        $activeTables = Table::where('status', 'occupied') // Chỉnh thành 'playing' thay vì 'confirmed')
            ->count();
        $usageRate = ($totalTables > 0) ? ($activeTables / $totalTables) * 100 : 0;
        
        return [
            'totalTables' => $totalTables,
            'activeTables' => $activeTables,
            'usageRate' => number_format($usageRate, 2)
        ];
    }

    public function index()
    {
        // Lấy số liệu thống kê bàn
        $stats = $this->calculateTableStats();
        
        // Lấy danh sách tất cả các bàn
        $tables = Table::all(); 
        $availableTables = Table::where('status', 'available')->get();
        
        return view('home', compact('tables', 'availableTables') + $stats);
    }

    // API để lấy dữ liệu bàn mà không cần load lại trang
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
