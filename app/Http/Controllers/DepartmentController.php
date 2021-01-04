<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('admin.department.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.department.create');
    }

    public function store(Request $request)
    {
        Department::create($request->all());
        return redirect()->back()->with('message', 'Department added successfully!');
    }

    public function edit($id)
    {
        $department = Department::find($id);
        return view('admin.department.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        $department->department = $request->department;
        $department->save();

        return redirect()->route('department.index')->with('message', 'Department updated successfully!');
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        $department->delete();

        return redirect()->route('department.index')->with('message', 'Department deleted successfully!');
    }
}
