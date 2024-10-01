<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Industry;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function index()
    {
        $data = Account::select('accounts.*', 'solution_sub_types.solution_sub_type_name', 'solution_types.solution_type_name', 'solutions.solution_name as solution', 'clients.name as clientname', 'clients.email as clientemail', 'clients.phone as clientphone', 'clients.location as clientlocation', 'clients.website_url as clientwebsiteurl', 'clients.industry_id', 'industries.name as industry', 'users.first_name as managerfirstname', 'users.last_name as managerlastname', 'users.phone as managerphone', 'users.email as manageremail', 'client_types.name as type')
            ->join('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
            ->leftjoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
            ->leftjoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
            ->leftjoin('clients', 'clients.id', '=', 'accounts.client_id')
            ->leftjoin('client_types', 'client_types.id', '=', 'clients.client_type_id')
            ->join('industries', 'industries.id', '=', 'clients.industry_id')
            ->join('users', 'users.id', '=', 'accounts.user_id')
            ->get();
        return Inertia::render('Clients/index', [
            'clients' => $data
        ]);
    }

    public function stg($i)
    {
   $latestProjectStageInfo = DB::table('project_stage_information')
    ->where('project_stage_information.project_stage_id', '=', $i)
    ->select('account_id', DB::raw('MAX(id) as latest_id'))
    ->groupBy('account_id');

    $latestProjectStageInfo = DB::table('project_stage_information')
    ->select('account_id', DB::raw('MAX(id) as latest_id'))
    ->where('project_stage_id', '=', $i)
    ->groupBy('account_id');

$data = Account::select(
        'accounts.*',
        'solution_sub_types.solution_sub_type_name',
        'solution_types.solution_type_name',
        'solutions.solution_name as solution',
        'clients.name as clientname',
        'clients.email as clientemail',
        'clients.phone as clientphone',
        'clients.location as clientlocation',
        'clients.website_url as clientwebsiteurl',
        'clients.industry_id',
        'industries.name as industry',
        'users.first_name as managerfirstname',
        'users.last_name as managerlastname',
        'users.phone as managerphone',
        'users.email as manageremail',
        'client_types.name as type'
    )
    ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
        $join->on('accounts.id', '=', 'latest_psi.account_id');
    })
    ->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
    ->join('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
    ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
    ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
    ->leftJoin('clients', 'clients.id', '=', 'accounts.client_id')
    ->leftJoin('client_types', 'client_types.id', '=', 'clients.client_type_id')
    ->join('industries', 'industries.id', '=', 'clients.industry_id')
    ->join('users', 'users.id', '=', 'accounts.user_id')
    ->whereNotNull('latest_psi.latest_id') // Ensure only accounts with matching latest project stage are included
    ->get();




        return Inertia::render('Clients/index', [
            'clients' => $data
        ]);
    }

    public function existing()
    {
        $personnels = User::where('sales_rep', 1)->where('is_active',1)->get();
        $data = Client::with('industry')->where('is_existing', true)
            ->get();
        $industries = Industry::all();
        return Inertia::render('Clients/existing', [
            'clients' => $data,
            'personnels' => $personnels,
            'industries' => $industries
        ]);
    }




    public function update_existing(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:clients,id',
            'name' => 'required|min:2|unique:clients,name,' . $request->id,
            'email' => 'required|email|unique:clients,email,' . $request->id,
            'phone' => 'required|min:10|max:14,' . $request->id,
            'location' => 'required',
            'industry_id' => 'required|exists:industries,id',
            'user_id' => 'required|exists:users,id',
            'comments' => 'max:1000',
            'website_url' => 'min:3',
            'contact_information' => 'required'
        ]);



        // $accountD = Client::query()->Where('name', 'ilike', "%$request->name%")->orWhere('email', 'ilike', "%$request->email%")->first();
        // if ($accountD) {
        //     return RespondWithError::run(...[
        //         'status' => 403,
        //         'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
        //         'message' => 'Client already exists!',
        //         'recommendation' => 'Try Signing In',
        //     ]);
        // }


        $client = Client::find($request->id);
        $client->email = $request->input('email');
        $client->name =  $request->input('name');
        $client->is_existing = true;
        $client->user_id = $request->input('user_id');
        $client->phone = $request->input('phone');
        $client->location = $request->input('location');
        $client->industry_id = $request->input('industry_id');
        $client->is_existing = true;
        $client->comments = $request->input('comments');
        $client->website_url = $request->input('website_url');
        $client->contact_information = json_encode($request->input('contact_information'));

        $client->saveOrFail();

        //$client->user_id = $credentials['user_id'];

        return back()->with('success', 'Client Updated Successfully');
    }


    public function create_existing(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:2|unique:clients,name',
            'email' => 'required|email|unique:clients,email|regex:/(.+)@(.+)\.(.+)/i',
            'phone' => 'required|min:10|max:14|unique:clients,phone',
            'location' => 'required',
            'industry_id' => 'required|exists:industries,id',
            'user_id' => 'required|exists:users,id',
            'comments' => 'max:1000',
            'website_url' => 'min:3',
            'contact_information' => 'required'
        ]);



        // $accountD = Client::query()->Where('name', 'ilike', "%$request->name%")->orWhere('email', 'ilike', "%$request->email%")->first();
        // if ($accountD) {
        //     return RespondWithError::run(...[
        //         'status' => 403,
        //         'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
        //         'message' => 'Client already exists!',
        //         'recommendation' => 'Try Signing In',
        //     ]);
        // }


        $client = new Client();
        $client->email = $request->input('email');
        $client->name =  $request->input('name');
        $client->is_existing = true;
        $client->phone = $request->input('phone');
        $client->location = $request->input('location');
        $client->user_id = $request->input('user_id');
        $client->industry_id = $request->input('industry_id');
        $client->is_existing = true;
        $client->comments = $request->input('comments');
        $client->website_url = $request->input('website_url');
        $client->contact_information = json_encode($request->input('contact_information'));

        $client->saveOrFail();

        //$client->user_id = $credentials['user_id'];

        return back()->with('success', 'Client Created Successfully');
    }





    public function upselling()
    {
        $data = Client::where('clients.client_type_id', 2)->select('clients.*', 'industries.name as industry')->join('industries', 'industries.id', '=', 'clients.industry_id')->get();
        return Inertia::render('Clients/cat', [
            'clients' => $data
        ]);
    }

    public function renewal()
    {
        $data = Client::where('clients.client_type_id', 3)->select('clients.*', 'industries.name as industry')->join('industries', 'industries.id', '=', 'clients.industry_id')->get();
        return Inertia::render('Clients/cat', [
            'clients' => $data
        ]);
    }

    public function new()
    {
        $data = Client::where('clients.client_type_id', 1)->select('clients.*', 'industries.name as industry')->join('industries', 'industries.id', '=', 'clients.industry_id')->get();
        return Inertia::render('Clients/cat', [
            'clients' => $data
        ]);
    }
}
