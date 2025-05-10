<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::with(['table', 'customer'])->get();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tables = Table::where('status', 'available')->get();
        return view('reservations.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
            'customer_email' => 'nullable|email',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:confirmed,playing,completed,cancelled',
        ]);

        try {
            // Kiểm tra xem bàn có sẵn sàng để đặt không
            $table = Table::findOrFail($request->table_id);
            if ($table->status !== 'available') {
                return redirect()->back()->with('error', 'Bàn không khả dụng để đặt.');
            }

            // Tạo mới khách hàng
            $customer = Customer::create([
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'email' => $request->customer_email,
            ]);

            // Tạo mới reservation với customer_id vừa tạo
            $reservation = Reservation::create([
                'table_id' => $request->table_id,
                'customer_id' => $customer->id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status,
            ]);

            // Cập nhật trạng thái của bàn thành "reserved"
            $table->status = 'occupied';
            $table->save();


            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt bàn thành công!'
                ]);
            }
            

            return redirect()->route('home')
                ->with('success', 'Đặt bàn thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi đặt bàn: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi đặt bàn.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        $tables = Table::all();
        $customers = Customer::all();
        return view('reservations.edit', compact('reservation', 'tables', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_id' => 'required|exists:customers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:confirmed,playing,completed,cancelled',
        ]);

        try {
            $reservation->update($request->all());
            return redirect()->route('reservations.index')
                ->with('success', 'Cập nhật đặt bàn thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi cập nhật đặt bàn: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật đặt bàn.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        try {
            // Cập nhật trạng thái của bàn thành "available" khi hủy đặt bàn
            $table = $reservation->table;
            $table->status = 'available';
            $table->save();

            $reservation->delete();
            return redirect()->route('reservations.index')
                ->with('success', 'Hủy đặt bàn thành công.');
        } catch (\Exception $e) {
            Log::error("Lỗi khi hủy đặt bàn: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi hủy đặt bàn.');
        }
    }

    public function cancel($table_id)
    {
        try {
            // Tìm đặt bàn theo table_id
            $reservation = Reservation::where('table_id', $table_id)->first();

            if (!$reservation) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy đặt bàn cho bàn này.'], 404);
            }

            // Cập nhật trạng thái bàn về "available"
            $table = Table::find($table_id);
            if ($table) {
                $table->status = 'available';
                $table->save();
            }
            $reservation->status = 'cancelled';
            $reservation->save();

            return response()->json(['success' => true, 'message' => 'Hủy đặt bàn thành công, bàn đã sẵn sàng.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi hủy đặt bàn.'], 500);
        }
    }
    public function confirmReservation($table_id)
{
    // Tìm đặt bàn theo table_id với trạng thái "confirmed"
    $reservation = Reservation::where('table_id', $table_id)
                              ->where('status', 'confirmed')
                              ->first();

    if (!$reservation) {
        return response()->json(['message' => 'Không tìm thấy đặt bàn cho bàn này!'], 404);
    }

    // Cập nhật trạng thái và thời gian bắt đầu
    $reservation->status = 'playing';
    $reservation->start_time = now();
    $reservation->save();

    // Cập nhật trạng thái bàn thành "occupied"
    $table = Table::find($table_id);
    if ($table) {
        $table->status = 'occupied';
        $table->save();
    }

    return response()->json(['message' => 'Bàn đã được xác nhận!']);
}
    public function getCustomerByTableId($table_id)
    {
        try {
            $reservation = Reservation::where('table_id', $table_id)
                                    ->whereIn('status', ['confirmed', 'playing'])
                                    ->first();

            if (!$reservation) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy đặt bàn cho bàn này.'], 404);
            }

            $customer = Customer::find($reservation->customer_id);
            $table = Table::find($reservation->table_id);

            return response()->json([
                'success' => true,
                'customer' => $customer,
                'reservation' => [
                    'start_time' => $reservation->start_time,
                    'end_time' => $reservation->end_time,
                    'status' => $reservation->status,
                    'hourly_rate' => $table->price_per_hour, // <-- lấy từ model Table
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin khách hàng.'
            ], 500);
        }
    }
    public function getByTable($tableId)
    {
        $reservation = Reservation::where('table_id', $tableId)
            ->whereIn('status', ['confirmed', 'playing']) // <-- Thay vì whereNull('end_time')
            ->with(['customer', 'table'])
            ->latest()
            ->first();
    
        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đặt bàn.'
            ]);
        }
    
        return response()->json([
            'success' => true,
            'reservation' => $reservation
        ]);
    }
    
}
