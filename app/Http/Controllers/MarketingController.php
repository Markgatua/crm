<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;



class MarketingController extends Controller
{
    public function index(){
        $data = Account::select(
            DB::raw('MAX(accounts.business_name) as account_name'),
            DB::raw('MAX(users.first_name) as accountmanagerfirstname'),
            DB::raw('MAX(users.last_name) as accountmanagerlastname'),
            DB::raw('MAX(users.email) as accountmanageremail'),
            DB::raw('MAX(users.phone) as accountmanagerphone'),
            DB::raw('MAX(client_types.name) as clienttype'),
            'clients.name as clientname',
            'clients.id as clientid',
            DB::raw('MAX(clients.email) as clientemail'),
            DB::raw('MAX(clients.phone) as clientphone'),
            DB::raw('MAX(clients.location) as clientlocation'),
            DB::raw('MAX(clients.website_url) as clientwebsiteurl'),
            DB::raw('MAX(clients.contact_information::text) as clientmaincontactpersons') // Cast JSON field to text
        )
        ->join('users', 'users.id', '=', 'accounts.user_id')
        ->join('client_types', 'client_types.id', '=', 'accounts.client_type_id')
        ->join('clients', 'clients.id', '=', 'accounts.client_id')
        ->groupBy('clients.id', 'clients.name') // Group by client ID and name
        ->get();

        // return json_encode($data);

        return Inertia::render('Marketing/index',[
            'accounts' => $data,
        ]);
    }
}
