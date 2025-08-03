<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('master-data-karyawan', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'department' => 'required',
            'hire_date' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'status' => 'required',
            'photo' => 'required|image|max:2048',
            'gender' => 'required|in:male,female',
            'born_place' => 'required',
            'born_date' => 'required|date',
        ]);

        $photo = $request->file('photo');
        $photoName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('public', $photoName);

        Employee::create([
            'name' => $request->name,
            'position' => $request->position,
            'department' => $request->department,
            'hire_date' => $request->hire_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
            'photo' => $photoName,
            'gender' => $request->gender,
            'born_place' => $request->born_place,
            'born_date' => $request->born_date,
        ]);

        return redirect()->route('master-data-karyawan')->with('success', 'Employee created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
            'department' => 'required',
            'hire_date' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'status' => 'required',
            'photo' => 'nullable|image|max:2048',
            'gender' => 'required|in:male,female',
            'born_place' => 'required',
            'born_date' => 'required|date',
        ]);

        $employee = Employee::findOrFail($id);

        $data = [
            'name' => $request->name,
            'position' => $request->position,
            'department' => $request->department,
            'hire_date' => $request->hire_date,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
            'gender' => $request->gender,
            'born_place' => $request->born_place,
            'born_date' => $request->born_date,
        ];

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }

            $photo = $request->file('photo');
            $photoName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public', $photoName);
            $data['photo'] = $photoName;
        }

        $employee->update($data);

        return redirect()->route('master-data-karyawan')->with('success', 'Data karyawan berhasil diupdate!');
    }

    public function getEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete photo if exists
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('master-data-karyawan')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
