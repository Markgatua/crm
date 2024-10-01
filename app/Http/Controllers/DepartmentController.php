<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;


class DepartmentController extends Controller
{
    public function index(){
        $data = Department::all();
        return Inertia::render('Settings/Departments/index',[
            'departments' => $data
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $role= new Department();
        $role->name=$request->input('name');
        $role->saveOrFail();
        return back()->with('success','Department Added Successfully');
    }

    public function update(Request $request, Department $department): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $department->name=$request->input('name');
        $department->updateOrFail();
        return back()->with('success','Department Edited Successfully!');
    }


    public function destroy(Department $department): \Illuminate\Http\RedirectResponse
    {
        $department->deleteOrFail();
        return back()->with('success','Department Deleted Successfully!');
    }
}
