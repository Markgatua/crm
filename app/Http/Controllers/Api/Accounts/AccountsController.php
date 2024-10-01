<?php

namespace App\Http\Controllers\Api\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;
use App\Models\User;
use App\Models\ClientType;
use App\Actions\Utils\RespondWithError;
use App\Actions\Utils\RespondWithSuccess;
use App\Http\Resources\AccountResource;
use App\Http\Resources\ProjectStageResource;
use App\Models\Documents;
use App\Models\ProjectStage;
use App\Models\ProjectStageInformation;
use App\Models\Revenue;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountsController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),
        [
            'business_name' => 'required|min:2',
            'comments' => 'max:1000',
            'contact_information'=>'required',
            'client_id' => 'required|exists:clients,id',
            'client_type_id' => 'required|exists:client_types,id',
            'user_id' => 'required|exists:users,id',
        ]);

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

        $accountD = Account::query()->Where('business_name',$request->business_name)->first();
        if ($accountD) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'Account Business already exists!',
                'recommendation' => 'Try Signing In',
            ]);
        }

        $account = new Account();
        $account->business_name = $credentials['business_name'];
        $account->solution_name = $request->solution_name;
        $account->client_id = $credentials['client_id'];
        $account->client_type_id = $credentials['client_type_id'];
        $account->solution_type_id = $request->solution_type_id;
        $account->user_id = $credentials['user_id'];
        $account->comments = $credentials['comments'];
        $account->contact_information = json_encode($credentials['contact_information']);
        $account->save();

        $todaydate = Carbon::now()->format('F j, Y');

        $projectinfo = new ProjectStageInformation();
        $projectinfo->project_stage_id = 1;
        $projectinfo->account_id = $account->id;
        $projectinfo->stage_information = "Account initiated on $todaydate";
        $projectinfo->meta = json_encode($request->meta);
        $projectinfo->save();

        // $updateClient = Client::where('id',$credentials['client_id'])->update(["client_type_id"=>$credentials["client_type_id"]]);

        if($account){
            $response  = [
                'error' => false,
                'message' => 'Successfully Created a New Account Business',
            ];
            return response()->json($response, 200);
        }else{
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to create a new Account Business!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(),
        [
            'id' => 'required|exists:accounts,id',
            'business_name' => 'required|min:2',
            'comments' => 'max:1000',
            'contact_information'=>'required',
            'client_id' => 'required|exists:clients,id',
            'client_type_id' => 'required|exists:client_types,id',
            'user_id' => 'required|exists:users,id',
        ]);

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

        $update = Account::where('id',$request->id)->update([
            'business_name'=>$credentials['business_name'],
            'client_type_id'=>$credentials['client_type_id'],
            'comments'=>$credentials['comments'],
            'client_id'=>$credentials['client_id'],
            'client_type_id'=>$credentials['client_type_id'],
            'user_id'=>$credentials['user_id'],
            'contact_information'=>json_encode($credentials['contact_information'])
        ]);

        // $updateClient = Client::where('id',$update->client_id)->update(["client_type_id"=>$credentials["client_type_id"]]);


        if($update){
            $response  = [
                'error' => false,
                'message' => 'Successfully Updated Account Business Details',
            ];
            return response()->json($response, 200);
        }else{
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to Update Account Business Data!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function view($id){
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Manager Does Not Exists',
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $data1 = Account::where('accounts.user_id', $id)
            ->select('accounts.*', 'users.first_name as accountmanagerfirstname', 'users.last_name as accountmanagerlastname', 'users.email as accountmanageremail', 'users.phone as accountmanagerphone', 'client_types.name as clienttype', 'clients.name as clientname', 'clients.id as clientid', 'clients.email as clientemail', 'clients.phone as clientphone', 'clients.location as clientlocation', 'clients.website_url as clientwebsiteurl', 'clients.contact_information as accountmaincontacts')
            ->join('users', 'users.id', '=', 'accounts.user_id')
            ->join('client_types', 'client_types.id', '=', 'accounts.client_type_id')
            ->join('clients', 'clients.id', '=', 'accounts.client_id')
            ->get();


        if(count($data1) == 0){
            $response = [
                'data' => [],
                'error' => false,
                'message' => 'Successfully Fetched Accounts',
            ];
            return response()->json($response, 200);
        }

        // Iterate over each item in $data1
        foreach ($data1 as $item) {
            $item->contact_information = json_decode($item->contact_information, true);
            $item->accountmaincontacts = json_decode($item->accountmaincontacts, true);
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'stage_meta' => json_decode($info->meta),
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                    'project_stage_created_at' => $info->created_at,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        if ($data1->isNotEmpty()) {
            $response = [
                'data' => $data1->toArray(),
                'error' => false,
                'message' => 'Successfully Fetched Accounts',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Clients!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }


    }

    public function accountWithStatus($id,$type){
        $validator = Validator::make(['id' => $id,'type'=>$type], [
            'id' => 'required|exists:users,id',
            'type' => 'required|exists:client_types,id'
        ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Manager or Client Type Does Not Exists',
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $data1 = Account::where('user_id',$id)->where('client_type_id',$type)->select('accounts.*','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as accountmaincontacts')
        ->join('users','users.id','=','accounts.user_id')
        ->join('client_types','client_types.id','=','accounts.client_type_id')
        ->join('clients','clients.id','=','accounts.client_id')
        ->get();
        foreach ($data1 as $item) {
            $item->contact_information = json_decode($item->contact_information, true);
            $item->accountmaincontacts = json_decode($item->accountmaincontacts, true);
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'stage_meta' => json_decode($info->meta),
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                    'project_stage_created_at' => $info->created_at,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        if ($data1->isNotEmpty()) {
            $response = [
                'data' => $data1->toArray(),
                'error' => false,
                'message' => 'Successfully Fetched Accounts',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Clients!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function client($id,$client){
        $validator = Validator::make(['id' => $id,'client'=>$client], [
            'id' => 'required|exists:users,id',
            'client' => 'required|exists:clients,id'
        ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Manager or Client Does Not Exists',
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $data1 = Account::where('accounts.user_id',$id)->where('accounts.client_id',$client)->select('accounts.*','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as accountmaincontacts')
        ->join('users','users.id','=','accounts.user_id')
        ->join('client_types','client_types.id','=','accounts.client_type_id')
        ->join('clients','clients.id','=','accounts.client_id')
        ->get();
        foreach ($data1 as $item) {
            $item->contact_information = json_decode($item->contact_information, true);
            $item->accountmaincontacts = json_decode($item->accountmaincontacts, true);
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'stage_meta' => json_decode($info->meta),
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                    'project_stage_created_at' => $info->created_at,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        if ($data1->isNotEmpty()) {
            $response = [
                'data' => $data1->toArray(),
                'error' => false,
                'message' => 'Successfully Fetched Accounts',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Clients!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function show($id){
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:accounts,id',
        ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Account Does Not Exists',
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $data1 = Account::where('accounts.id',$id)->select('accounts.*','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as accountmaincontacts')
        ->join('users','users.id','=','accounts.user_id')
        ->join('client_types','client_types.id','=','accounts.client_type_id')
        ->join('clients','clients.id','=','accounts.client_id')
        ->get();

        foreach ($data1 as $item) {
            $item->contact_information = json_decode($item->contact_information, true);
            $item->accountmaincontacts = json_decode($item->accountmaincontacts, true);
            // Fetch corresponding ProjectStageInformation for the current $item
            $data2 = ProjectStageInformation::where('account_id', $item->id)->get();

            // Transform $data2
            $transformedData2 = $data2->map(function ($info) {
                return [
                    'stage_information' => $info->stage_information,
                    'stage_meta' => json_decode($info->meta),
                    'project_stage_id' => $info->project_stage_id,
                    'project_stage_name' => ProjectStage::where('id',$info->project_stage_id)->first()->name,
                    'project_stage_created_at' => $info->created_at,
                ];
            });

            // Merge transformedData2 with current $item
            $item->project_stage_information = $transformedData2->toArray();
        }

        if ($data1->isNotEmpty()) {
            $response = [
                'data' => $data1->toArray(),
                'error' => false,
                'message' => 'Successfully Fetched Accounts',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Clients!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function delete($id){
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:accounts,id',
        ]);

        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Client Does Not Exist',
                "payload" => $validator->errors()->toArray()
            ]);
        }

        try {
            DB::beginTransaction();
            $delete = Account::findOrFail($id);
            $delete->delete();

            DB::commit();

            $response = [
                'error' => false,
                'message' => 'Successfully Deleted Account',
            ];
            return response()->json($response, 200);

        } catch (\Exception $e) {
            DB::rollback();

            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to Delete Account!',
                'recommendation' => 'Contact Admin',
            ]);
        }
    }

    public function clientTypes(){
        $data = ClientType::all();
        if($data){
            $response  = [
                'data' => ClientTypeResource::collection($data),
                'error' => false,
                'message' => 'Successfully Fetched Client Types',
            ];
            return response()->json($response, 200);
            }else{
                return RespondWithError::run(...[
                    'status' => 403,
                    'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                    'message' => 'Failed to fetch Client Types Data!',
                    'recommendation' => 'Try Again, if the issue persists contact Admin',
                ]);
            }
    }

    public function updateStage(Request $request){
        //return $request->all();
        $validator = Validator::make($request->all(),
        [
            'id' => 'required|exists:accounts,id',
            'project_stage_id' => 'required|exists:project_stages,id',
            'stage_information' => 'required|max:1000',
        ]);

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

        $metaData = json_decode($request->meta, true);
        $cleanMeta = json_encode($metaData);

        $projectinfo = new ProjectStageInformation();
        $projectinfo->account_id = $credentials['id'];
        $projectinfo->project_stage_id = $credentials['project_stage_id'];
        $projectinfo->stage_information = $credentials['stage_information'];
        $projectinfo->meta = $cleanMeta;
        $projectinfo->save();



        if ($request->has('file')) {
            $documents = $request->file;

            $file = $request->file("file");
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);

            $document = new Documents();
            $document->account_id = $credentials['id'];
            $document->project_stage_information_id = $projectinfo->id;
            $document->document_type_id = 2;
            $document->document_name = $request->file_name;
            $document->document_link = 'uploads/' . $fileName;
            $document->save();
        }

        if($projectinfo){
            $response  = [
                'error' => false,
                'message' => 'Successfully Updated Account',
            ];
            return response()->json($response, 200);
        }else{
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to Update Account!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function stages(){
        $data = ProjectStage::all();
        if($data){
            $response  = [
                'data' => ProjectStageResource::collection($data),
                'error' => false,
                'message' => 'Successfully Fetched Project Stages',
            ];
            return response()->json($response, 200);
            }else{
                return RespondWithError::run(...[
                    'status' => 403,
                    'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                    'message' => 'Failed to fetch Project Stages Data!',
                    'recommendation' => 'Try Again, if the issue persists contact Admin',
                ]);
            }
    }
}
