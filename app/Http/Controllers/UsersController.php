<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
Use App\Models\User;
Use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(){
        $data = User::select('users.*','departments.name as department','roles.name as role')
                        ->join('departments','departments.id','=','users.department_id')
                        ->join('roles','roles.id', '=', 'users.role_id')
                        ->get();
        $departments = Department::all();
        $roles = Role::all();
        return Inertia::render('Users/index',[
            'users' => $data,
            'departments' => $departments,
            'roles' => $roles,
        ]);
    }

    public function sales(){
        $data = User::where('users.sales_rep',1)->select('users.*','departments.name as department','roles.name as role')
                        ->join('departments','departments.id','=','users.department_id')
                        ->join('roles','roles.id', '=', 'users.role_id')
                        ->get();
        return Inertia::render('Users/personnels',[
            'users' => $data,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'first_name'=>'required|string|min:2|max:100',
            'last_name'=>'required|string|min:2|max:100',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|max:20',
            'phone' => 'required|min:10|max:10|unique:users,phone',
            'salesPerson' => 'required',
            'email' => 'required|email|unique:users,email|regex:/(.+)@(.+)\.(.+)/i',
            'password' => [
                'required',
                'min:8',
                'max:12',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).+$/'
            ]

        ]);
        $user= new User();
        $user->first_name=$request->input('first_name');
        $user->last_name=$request->input('last_name');
        $user->designation=$request->input('designation');
        $user->role_id=$request->input('role_id');
        $user->department_id=$request->input('department_id');
        $user->phone=$request->input('phone');
        $user->calling_code = '254';
        $user->email=$request->input('email');
        $user->sales_rep =$request->input('salesPerson');
        $user->password=Hash::make($request->input('password'));
        $user->saveOrFail();


        return back()->with('success','User Created Successfully');
    }

    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'first_name'=>'required|string|min:2|max:100',
            'last_name'=>'required|string|min:2|max:100',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|max:20',
            'phone' => 'required|min:10|max:10',
            'salesPerson' => 'required',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i'
        ]);
        $user->first_name=$request->input('first_name');
        $user->last_name=$request->input('last_name');
        $user->designation=$request->input('designation');
        $user->role_id=$request->input('role_id');
        $user->department_id=$request->input('department_id');
        $user->phone=$request->input('phone');
        $user->calling_code = '254';
        $user->email=$request->input('email');
        $user->sales_rep =$request->input('salesPerson');
        $user->password=Hash::make($request->input('password'));
        $user->updateOrFail();


        return back()->with('success','User Edited Successfully');
    }

    public function switch(Request $request): \Illuminate\Http\RedirectResponse
    {
        $id = $request->id;
        $user = User::where('id',$id)->first();
        if($user->is_active == true){
            $user->is_active = false;
            $user->save();
        }else{
            $user->is_active = true;
            $user->save();
        }

        return back()->with('success','User Status Updated Successfully!');
    }


    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->deleteOrFail();
        return back()->with('success','User Deleted Successfully!');
    }
}
