<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $booking = Booking::create($request->all());

        // Cập nhật trạng thái bàn thành "occupied"
        Table::where('id', $request->table_id)->update(['status' => 'occupied']);

        return response()->json(['message' => 'Đặt bàn thành công!', 'booking' => $booking], 200);
    }
}
