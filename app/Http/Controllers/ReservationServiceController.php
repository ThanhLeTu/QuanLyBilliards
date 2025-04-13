<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservationService; // Model cho ReservationService (nếu có)
use App\Models\Reservation; // Model cho Reservation

class ReservationServiceController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:0', // 👈 cho phép bằng 0
        ]);
    
        $reservationId = $request->reservation_id;
        $serviceId = $request->service_id;
        $quantity = $request->quantity;
    
        $record = ReservationService::where('reservation_id', $reservationId)
            ->where('service_id', $serviceId)
            ->first();
    
        if ($quantity == 0) {
            // Nếu số lượng là 0 -> XÓA
            if ($record) {
                $record->delete();
            }
    
            return response()->json(['success' => true, 'message' => 'Xóa dịch vụ thành công.']);
        }
    
        // Nếu số lượng > 0 -> CẬP NHẬT hoặc TẠO MỚI
        if ($record) {
            $record->quantity = $quantity;
            $record->save();
        } else {
            ReservationService::create([
                'reservation_id' => $reservationId,
                'service_id' => $serviceId,
                'quantity' => $quantity,
            ]);
        }
    
        return response()->json(['success' => true, 'message' => 'Cập nhật dịch vụ thành công.']);
    }
}
