<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Account;
Use App\Models\Client;
Use App\Models\User;
Use App\Models\ProjectStageInformation;
Use App\Models\ProjectStage;
use Inertia\Inertia;

class AccountsController extends Controller
{
    public function index(){

    }

    public function show($id){
        $data1 = Account::where('client_id',$id)->select('accounts.*','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.main_contact_person_name','clients.main_contact_person_email','clients.main_contact_person_phone','clients.main_contact_person_designation')
        ->join('users','users.id','=','accounts.user_id')
        ->join('client_types','client_types.id','=','accounts.client_type_id')
        ->join('clients','clients.id','=','accounts.client_id')
        ->get();
        foreach ($data1 as $item) {
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'due_date' => $info->due_date,
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                    'project_stage_created_at' => $info->created_at,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        $titlename = Client::where('id',$id)->first();

        return Inertia::render('Accounts/client',[
            'accounts' => $data1->toArray(),
            'titlename' => $titlename->name,
        ]);

    }

    public function user($id){
        $data1 = Account::where('accounts.user_id',$id)->select('accounts.*','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as clientcontacts')
        ->join('users','users.id','=','accounts.user_id')
        ->leftjoin('client_types','client_types.id','=','accounts.client_type_id')
        ->join('clients','clients.id','=','accounts.client_id')
        ->get();

        foreach ($data1 as $item) {
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                    'project_stage_created_at' => $info->created_at,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        $titlename = User::where('id',$id)->first();

        return Inertia::render('Accounts/client',[
            'accounts' => $data1->toArray(),
            'titlename' => $titlename->first_name.' '.$titlename->last_name ?? '',
        ]);

    }

    public function account($id){
        $data1 = Account::where('accounts.id',$id)->select('accounts.*','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information')
        ->join('users','users.id','=','accounts.user_id')
        ->leftjoin('client_types','client_types.id','=','accounts.client_type_id')
        ->join('clients','clients.id','=','accounts.client_id')
        ->get();
        foreach ($data1 as $item) {
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'due_date' => $info->due_date,
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        return Inertia::render('Accounts/account',[
            'accounts' => $data1->toArray()
        ]);

    }

}
