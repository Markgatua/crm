<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Inertia\Inertia;


class RolesController extends Controller
{
    public function index(){
        $data = Role::all();
        return Inertia::render('Settings/Roles/index',[
            'roles' => $data
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100',
            'description'=>'required|string|min:10|max:255'
        ]);
        $role= new Role();
        $role->name=$request->input('name');
        $role->description=$request->input('description');
        $role->saveOrFail();
        return back()->with('success','Role Added Successfully');
    }

    public function update(Request $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100',
            'description'=>'required|string|min:10|max:255'
        ]);
        $role->name=$request->input('name');
        $role->description=$request->input('description');
        $role->updateOrFail();
        return back()->with('success','Role Edited Successfully!');
    }


    public function destroy(Role $role): \Illuminate\Http\RedirectResponse
    {
        $role->deleteOrFail();
        return back()->with('success','Role Deleted Successfully!');
    }
}
