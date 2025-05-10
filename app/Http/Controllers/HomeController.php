<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Service;
use App\Models\ReservationService;
use App\Models\Booking;
use App\Models\BookingService;

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
        $tables = Table::all()->map(function($table) {
            $statusMap = [
                'available' => [
                    'class' => 'border-success',
                    'badge' => 'bg-success',
                    'text' => 'Trống'
                ],
                'occupied' => [
                    'class' => 'border-warning',
                    'badge' => 'bg-warning',
                    'text' => 'Đang sử dụng'
                ],
                'unavailable' => [
                    'class' => 'border-danger',
                    'badge' => 'bg-danger',
                    'text' => 'Không khả dụng'
                ]
            ];

            $status = $statusMap[$table->status] ?? [
                'class' => '',
                'badge' => 'bg-secondary',
                'text' => 'Không xác định'
            ];

            return [
                'id' => $table->id,
                'name' => "Bàn {$table->table_number}",
                'type' => $table->table_type,
                'area' => $table->area,
                'price' => $table->price,
                'description' => $table->description,
                'status_class' => $status['class'],
                'status_badge' => $status['badge'],
                'status_text' => $status['text'],
                'is_available' => $table->status === 'available'
            ];
        });

        $services = Service::all();
        $booking = null; // Or fetch active booking if exists

        return view('home', compact('tables', 'services', 'booking'));
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
