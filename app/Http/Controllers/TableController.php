<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Reservation;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {

        $tables = Table::all(); // Fetch all tables from the database
        return view('tables.index', compact('tables')); // Pass the tables to the view

    }
    

    /**
     * Display a listing of the resource for dataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $tables = Table::all();
        return response()->json($tables);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'table_number' => 'required|unique:tables',
                 'status' => 'required|in:available,occupied,unavailable',              
                'area' => 'required',
                'table_type' => 'required',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable',
            ]);

            $table = Table::create($request->all());
            
            return response()->json($table, 201);
        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo bàn: " . $e->getMessage() . "\n" . $e->getTraceAsString());

            return response()->json(['message' => 'Bàn đã tồn tại!'], 500);

        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Table $table)
    {
        return response()->json($table);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|unique:tables,table_number,'.$table->id,
            'status' => 'required|in:available,occupied,unavailable','area' => 'required',
            'table_type' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
        ]);

        $table->update($request->all());

        return response()->json($table);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Table  $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Table $table)
    {
        // Check if the table is referenced in any reservations
        $reservations = Reservation::where('table_id', $table->id)->count();
        if ($reservations > 0) {
            return response()->json(['message' => 'Không thể xóa bàn này vì nó đang được đặt!'], 400);
        }

        $table->delete();

        return response()->json(null, 204);
    }
}