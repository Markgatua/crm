<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Industry;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class SPAccountsController extends Controller
{
    public function index(){
        $user_id = Auth::user()->id;
        $data = Client::with('industry')->where('user_id', $user_id)
            ->get();
        $industries = Industry::all();
        return Inertia::render('SalesPerson/Accounts/index',[
            'clients' => $data,
            'industries' => $industries
        ]);
    }


public function create(Request $request)
{
    // Define validation rules
    $rules = [
        'name' => 'required|min:2|unique:clients,name',
        'email' => 'required|email|unique:clients,email|regex:/(.+)@(.+)\.(.+)/i',
        'phone' => 'required|min:10|max:14|unique:clients,phone',
        'location' => 'required',
        'industry_id' => 'required|exists:industries,id',
        'comments' => 'max:1000',
        'website_url' => 'min:3',
        'contact_information' => 'required'
    ];

    // Create a validator instance and validate the request
    $validator = Validator::make($request->all(), $rules);

    // Check if validation fails
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    try {
        // Create a new client instance and save the validated data
        $client = new Client();
        $client->email = $request->input('email');
        $client->name = $request->input('name');
        $client->phone = $request->input('phone');
        $client->location = $request->input('location');
        $client->industry_id = $request->input('industry_id');
        $client->user_id = Auth::user()->id;
        $client->comments = $request->input('comments');
        $client->website_url = $request->input('website_url');
        $client->contact_information = json_encode($request->input('contact_information'));

        $client->saveOrFail();

        return back()->with('success', 'Account Created Successfully');
    } catch (\Exception $e) {
        // Handle the exception if saveOrFail fails
        return back()->withErrors('error', 'Failed to create account. Please try again.');
    }
}


public function update(Request $request)
{

    $id = $request->query('id');
    // Define validation rules
    $rules = [
        'name' => ['required', 'min:2', Rule::unique('clients', 'name')->ignore($id)],
        'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($id), 'regex:/(.+)@(.+)\.(.+)/i'],
        'phone' => ['required', 'min:10', 'max:14', Rule::unique('clients', 'phone')->ignore($id)],
        'location' => 'required',
        'industry_id' => 'required|exists:industries,id',
        'comments' => 'max:1000',
        'website_url' => 'min:3',
        'contact_information' => 'required'
    ];


    // Create a validator instance and validate the request
    $validator = Validator::make($request->all(), $rules);
    // $validatedData = $request->validate($rules);


    // Check if validation fails
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }
    // return json_encode("hello");


    try {


        // Create a new client instance and save the validated data
        $client = Client::find($id);
        $client->email = $request->input('email');
        $client->name = $request->input('name');
        $client->phone = $request->input('phone');
        $client->location = $request->input('location');
        $client->industry_id = $request->input('industry_id');
        $client->user_id = Auth::user()->id;
        $client->comments = $request->input('comments');
        $client->website_url = $request->input('website_url');
        $client->contact_information = json_encode($request->input('contact_information'));

        $client->saveOrFail();

        return back()->with('success', 'Account Updated Successfully');
    } catch (\Exception $e) {
        // Handle the exception if saveOrFail fails
        return back()->withErrors(['error' => 'Failed to Update account. Please try again.']);
    }
}


}
