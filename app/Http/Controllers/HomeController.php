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
        $activeTables = Reservation::where('status', 'confirmed')
            ->whereHas('table', function ($query) {
                $query->where('status', 'occupied');
            })
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
        // Sử dụng phương thức tính toán thống kê
        $stats = $this->calculateTableStats();
        
        // Lấy danh sách bàn
        $tables = Table::all();
        $availableTables = Table::where('status', 'available')->get();
        
        return view('home', array_merge($stats, [
            'tables' => $tables,
            'availableTables' => $availableTables
        ]));
    }

    public function getTableStats()
    {
       // Lấy thống kê cơ bản
    $stats = $this->calculateTableStats();
    
    // Thêm danh sách bàn có sẵn
    $availableTables = Table::where('status', 'available')->get();
    
    return response()->json(array_merge($stats, [
        'availableTables' => $availableTables
    ]));
    }
}
