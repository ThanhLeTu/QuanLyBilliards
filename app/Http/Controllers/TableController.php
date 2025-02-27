<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;


class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function index()
    {
        return view('tables.index'); 
    }
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
        $request->validate([
            'table_number' => 'required|unique:tables',
            'status' => 'required|in:available,occupied,unavailable',
        ]);

        $table = Table::create($request->all());

        return response()->json($table, 201); // 201 Created
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
            'status' => 'required|in:available,occupied,unavailable',
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
        $table->delete();

        return response()->json(null, 204); // 204 No Content
    }
}