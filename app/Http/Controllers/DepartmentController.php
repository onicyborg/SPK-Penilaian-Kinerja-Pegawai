<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('master-data-karyawan')->with('success', 'Department created successfully.');
    }

    public function get($id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id . ',id',
        ]);

        $department->update([
            'name' => $request->name,
        ]);

        return redirect()->route('master-data-karyawan')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('master-data-karyawan')->with('success', 'Department deleted successfully.');
    }
}
