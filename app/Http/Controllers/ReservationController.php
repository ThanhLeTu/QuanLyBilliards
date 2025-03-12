<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::all();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tables = Table::all();
        $customers = Customer::all();
        $services = Service::all();
        return view('reservations.create', compact('tables', 'customers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_id' => 'required|exists:customers,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'status' => 'required|in:pending,confirmed,playing,completed,cancelled',
            'services' => 'array',
            'services.*.id' => 'exists:services,id',
            'services.*.quantity' => 'integer|min:1',
        ]);

        $reservation = Reservation::create($request->all());

        if ($request->has('services')) {
            foreach ($request->services as $service) {
                ReservationService::addServiceToReservation($reservation->id, $service['id'], $service['quantity']);
            }
        }

        return redirect()->route('reservations.index')
                        ->with('success','Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        $totalCost = $reservation->calculateTotalCost();
        return view('reservations.show', compact('reservation', 'totalCost'));
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
        $services = Service::all();
        return view('reservations.edit', compact('reservation', 'tables', 'customers', 'services'));
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
            'end_time' => 'nullable|date|after:start_time',
            'status' => 'required|in:pending,confirmed,playing,completed,cancelled',
            'services' => 'array',
            'services.*.id' => 'exists:services,id',
            'services.*.quantity' => 'integer|min:1',
        ]);

        $reservation->update($request->all());

        // Cập nhật dịch vụ
        $reservation->reservationServices()->delete();
        if ($request->has('services')) {
            foreach ($request->services as $service) {
                ReservationService::addServiceToReservation($reservation->id, $service['id'], $service['quantity']);
            }
        }

        return redirect()->route('reservations.index')
                        ->with('success','Reservation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('reservations.index')
                        ->with('success','Reservation deleted successfully');
    }
}