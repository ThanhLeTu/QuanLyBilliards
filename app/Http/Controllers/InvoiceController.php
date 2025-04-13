<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceService;
use App\Models\Table;
use App\Models\Reservation;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        $invoice = Invoice::create([
            'reservation_id'     => $request->reservation_id,
            'customer_name'      => $request->customer_name,
            'customer_phone'     => $request->customer_phone,
            'customer_note'      => $request->customer_note,
            'table_name'         => $request->table_name,
            'start_time'         => $request->start_time,
            'end_time'           => $request->end_time,
            'play_time_minutes'  => preg_replace('/[^0-9]/', '', $request->duration),
            'table_price'        => $request->table_price,
            'play_cost'          => $request->table_cost,
            'services_cost'      => $request->service_cost,
            'total_payment'      => $request->total_cost,
        ]);
        
        // Lưu dịch vụ nếu có
        if (is_array($request->services)) {
            foreach ($request->services as $item) {
                $invoice->usedServices()->create([
                    'service_id' => $item['service_id'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }            
        }
    
        // Cập nhật trạng thái bàn về "available"
        $reservation = Reservation::find($request->reservation_id);
        if ($reservation && $reservation->table_id) {
            Table::where('id', $reservation->table_id)->update(['status' => 'available']);
        }
    
        return response()->json(['invoice_id' => $invoice->id]);
    }
    

    public function show($id)
    {
        $invoice = Invoice::with('usedServices')->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }
    
}
