<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // Hiển thị danh sách nhân viên
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    // Hiển thị form thêm mới
    public function create()
    {
        return view('employees.create');
    }

    // Lưu nhân viên mới
    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:employees,email',
            'phone'              => 'nullable|string|max:20',
            'position'           => 'required|string',
            'gender'             => 'required|in:Nam,Nữ,Khác',
            'birth_date'         => 'required|date',
            'start_date'         => 'required|date',
            'salary_per_month'   => 'required|numeric|min:0',
            'avatar'             => 'nullable|image|max:2048',
            'citizen_id_image'   => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['avatar', 'citizen_id_image']);

        // Xử lý upload ảnh
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('employees', 'public');
        }

        if ($request->hasFile('citizen_id_image')) {
            $data['citizen_id_image'] = $request->file('citizen_id_image')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công!');
    }

    // Hiển thị form sửa nhân viên
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    // Cập nhật thông tin nhân viên
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:employees,email,' . $employee->id,
            'phone'              => 'nullable|string|max:20',
            'position'           => 'required|string',
            'gender'             => 'required|in:Nam,Nữ,Khác',
            'birth_date'         => 'required|date',
            'start_date'         => 'required|date',
            'salary_per_month'   => 'required|numeric|min:0',
            'avatar'             => 'nullable|image|max:2048',
            'citizen_id_image'   => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['avatar', 'citizen_id_image']);

        // Xử lý cập nhật ảnh
        if ($request->hasFile('avatar')) {
            if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
            $data['avatar'] = $request->file('avatar')->store('employees', 'public');
        }

        if ($request->hasFile('citizen_id_image')) {
            if ($employee->citizen_id_image) Storage::disk('public')->delete($employee->citizen_id_image);
            $data['citizen_id_image'] = $request->file('citizen_id_image')->store('employees', 'public');
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    // Xoá nhân viên
    public function destroy(Employee $employee)
    {
        if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
        if ($employee->citizen_id_image) Storage::disk('public')->delete($employee->citizen_id_image);

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Đã xoá nhân viên!');
    }
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

}