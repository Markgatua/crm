<?php

namespace App\Http\Controllers;

use App\Models\SolutionType;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SolutionTypesController extends Controller
{
    public function index(){
        $data = SolutionType::all();
        return Inertia::render('Settings/SolutionType/index',[
            'solutionType' => $data
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $role= new SolutionType();
        $role->solution_type_name=$request->input('name');
        $role->saveOrFail();
        return back()->with('success','Solution Type Added Successfully');
    }

    public function update(Request $request, SolutionType $solutionType): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $solutionType->solution_type_name=$request->input('name');
        $solutionType->updateOrFail();
        return back()->with('success','Solution Type Edited Successfully!');
    }


    public function destroy(SolutionType $solutionType): \Illuminate\Http\RedirectResponse
    {
        $solutionType->deleteOrFail();
        return back()->with('success','Solution Type Deleted Successfully!');
    }
}
