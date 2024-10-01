<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use App\Models\SolutionType;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function index(){
        $solutiontypes = SolutionType::all();
        $data = Solution::join('solution_types','solutions.solution_type_id','=','solution_types.id')
            ->get();
        return Inertia::render('Settings/Solution/index',[
            'solution' => $data,
            'solutiontypes' => $solutiontypes,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = $request->validate([
            'solution_type_id' => ['required', 'exists:solution_types,id'],
            'name' => 'required|string|min:2|max:100|unique:solutions,solution_name'
        ]);

        // Check if validation fails
        if (!$validator) {
            return back()->withErrors($validator)->withInput();
        }

        $role= new Solution();
        $role->solution_type_id=$request->input('solution_type_id');
        $role->solution_name=$request->input('name');
        $role->save();
        return back()->with('success','Solution Added Successfully');
    }

    public function update(Request $request, Solution $solution): \Illuminate\Http\RedirectResponse
    {
        $validator = $request->validate([
            'solution_type_id' => ['required', 'exists:solution_types,id'],
            'name' => 'required|string|min:2|max:100|unique:solutions,solution_name'
        ]);

        // Check if validation fails
        if (!$validator) {
            return back()->withErrors($validator)->withInput();
        }

        $solution->solution_type_id=$request->input('solution_type_id');
        $solution->solution_name=$request->input('name');
        $solution->updateOrFail();
        return back()->with('success','Solution Edited Successfully!');
    }


    public function destroy(Solution $solution): \Illuminate\Http\RedirectResponse
    {
        $solution->deleteOrFail();
        return back()->with('success','Solution Deleted Successfully!');
    }
}
