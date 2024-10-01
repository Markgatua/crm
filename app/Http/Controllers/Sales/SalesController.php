<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\ProjectStage;
use App\Models\ProjectStageInformation;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Actions\Utils\RespondWithError;
use App\Actions\Utils\RespondWithSuccess;

class SalesController extends Controller
{
    public function selectClients(){
        $data = Client::all();
        if($data){
            $response  = [
                'error' => false,
                'message' => 'Successfully Fetched Existing Clients',
                'data' => $data
            ];
            return response()->json($response, 200);
        }else{
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'An error occured while fetching Existing Clients',
                'recommendation' => 'Reload to try again',
            ]);
        }
    }
    public function createAccount(Request $request){
        if($request->client_id == null){
            $validator = Validator::make($request->all(),
            [
                'client_name' => 'required|min:2',
                'client_email' => 'required|email|unique:clients,email|regex:/(.+)@(.+)\.(.+)/i',
                'client_phone' => 'required|min:10|max:14',
                'client_location' => 'required',
                'industry_id' => 'required|exists:industries,id',
                'contact_person_name' => 'required',
                'project_name' => 'required',
                'contact_person_email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
                'contact_person_phone' => 'required|min:10|max:14',
                'contact_person_designation' => 'required',
                'contact_person_other_information' => 'max:1000',
                'client_type_id' => 'required|exists:client_types,id',
                'project_stage_id' => 'required|exists:project_stages,id',
                'project_stage_information' => 'max:1000'
            ]);
        }else{
            $validator = Validator::make($request->all(),
                [
                    'client_id' => 'required|exists:clients,id',
                    'project_name' => 'required',
                    'contact_person_name' => 'required',
                    'contact_person_email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
                    'contact_person_phone' => 'required|min:10|max:14',
                    'contact_person_designation' => 'required',
                    'contact_person_other_information' => 'max:1000',
                    'client_type_id' => 'required|exists:client_types,id',
                    'project_stage_id' => 'required|exists:project_stages,id',
                    'project_stage_information' => 'max:1000'
                ]);
        }
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Fill all required fields correctly while Observing Rules',
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $credentials = $validator->validated();

        if($request->client_id == null){
        $client = new Client();
        $client->email = $credentials['client_email'];
        $client->name = $credentials['client_name'];
        $client->phone = $credentials['client_phone'];
        $client->location = $credentials['client_location'];
        $client->industry_id = $credentials['industry_id'];
        $client->save();

        $clientID = $client->id;
        }else{
            $clientID = $credentials['client_id'];
        }

        $accountD = Account::query()->where('client_id',$clientID)->Where('project_name', 'ilike',"%$request->project_name%")->first();
        if ($accountD) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'Account already exists!',
                'recommendation' => 'Try Signing In',
            ]);
        }

        $account = new Account();
        $account->project_name = $credentials['project_name'];
        $account->name = $credentials['contact_person_name'];
        $account->phone = $credentials['contact_person_phone'];
        $account->email = $credentials['contact_person_email'];
        $account->designation = $credentials['contact_person_designation'];
        $account->other_information = $credentials['contact_person_other_information'];
        $account->client_id = $clientID;
        $account->client_type_id = $credentials['client_type_id'];
        $account->save();

        $project = new ProjectStageInformation();
        $project->account_id = $account->id;
        $project->project_stage_id = $credentials['project_stage_id'];
        $project->stage_information = $credentials['project_stage_information'];
        $project->save();

        $response  = [
            'error' => false,
            'message' => 'Successfully Created a New Account',
        ];
        return response()->json($response, 200);
    }
}
