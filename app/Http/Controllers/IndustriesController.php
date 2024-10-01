<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustriesController extends Controller
{
    public function index(){
        $data = Industry::all();
        return Inertia::render('Settings/Industries/index',[
            'industries' => $data
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $role= new Industry();
        $role->name=$request->input('name');
        $role->saveOrFail();
        return back()->with('success','Industry Added Successfully');
    }

    public function update(Request $request, Industry $industry): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $industry->name=$request->input('name');
        $industry->updateOrFail();
        return back()->with('success','Industry Edited Successfully!');
    }


    public function destroy(Industry $Industry): \Illuminate\Http\RedirectResponse
    {
        $Industry->deleteOrFail();
        return back()->with('success','Industry Deleted Successfully!');
    }
}
