<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Solution;
use App\Models\SolutionType;
use Illuminate\Http\Request;
use App\Models\SolutionSubType;

class SolutionSubTypeController extends Controller
{
    public function index(){
        $solutiontypes = SolutionType::all();
        $solutions = Solution::all();
        $data = Solution::join('solution_sub_types','solution_sub_types.solution_id','=','solutions.id')
            ->join('solution_types','solution_types.id','=','solutions.solution_type_id')
            ->get();

        return Inertia::render('Settings/SolutionSubType/index',[
            'solutionTypes' => $solutiontypes,
            'solutions' => $solutions,
            'solutionSubType' => $data
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100',
            'solution_id' => 'required|exists:solutions,id'
        ]);

        $role= new SolutionSubType();
        $role->solution_sub_type_name=$request->input('name');
        $role->solution_id = $request->input('solution_id');
        $role->save();
        return back()->with('success','Solution Sub Type Added Successfully');
    }

    public function update(Request $request, SolutionSubType $solutionSubType): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'=>'required|string|min:2|max:100'
        ]);
        $solutionSubType->solution_type_name=$request->input('name');
        $solutionSubType->updateOrFail();
        return back()->with('success','Solution Sub Type Edited Successfully!');
    }


    public function destroy(SolutionSubType $solutionSubType): \Illuminate\Http\RedirectResponse
    {
        $solutionSubType->deleteOrFail();
        return back()->with('success','Solution Sub Type Deleted Successfully!');
    }
}
