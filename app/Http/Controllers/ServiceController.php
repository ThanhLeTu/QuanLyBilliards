<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('services.index');
    }

    /**
     * Get all services for DataTables/Ajax.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function data()
    {
        $services = Service::all();
        return response()->json($services);
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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'category' => 'required|in:drink,food,other',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $serviceData = $request->except('image');

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services', 'public');
                $serviceData['image'] = basename($imagePath);
            }

            $service = Service::create($serviceData);

            return response()->json([
                'message' => 'Service created successfully',
                'service' => $service
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating service: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create service'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Service $service)
    {
        try {
            return response()->json($service);
        } catch (\Exception $e) {
            Log::error('Error fetching service: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch service'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Service $service)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'category' => 'required|in:drink,food,other',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $serviceData = $request->except('image');

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($service->image) {
                    Storage::disk('public')->delete('services/' . $service->image);
                }
                $imagePath = $request->file('image')->store('services', 'public');
                $serviceData['image'] = basename($imagePath);
            }

            $service->update($serviceData);

            return response()->json([
                'message' => 'Service updated successfully',
                'service' => $service
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating service: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update service'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            
            // Xóa file ảnh cũ nếu có
            if ($service->image) {
                Storage::delete('public/services/' . $service->image);
            }
            
            // Xóa dịch vụ
            $service->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa dịch vụ thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa dịch vụ'
            ], 500);
        }
    }
    
}