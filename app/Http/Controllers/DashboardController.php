<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Table;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng số bàn
        $totalTables = Table::count();

        // Thống kê tổng số hóa đơn
        $totalInvoices = Invoice::count();

        // Thống kê tổng doanh thu
        $totalRevenue = Invoice::sum('total_payment');

        // Thống kê số lượng dịch vụ đã thanh toán
        $totalServices = Service::count();

        return view('dashboard.index', compact('totalTables', 'totalInvoices', 'totalRevenue', 'totalServices'));
    }
}
