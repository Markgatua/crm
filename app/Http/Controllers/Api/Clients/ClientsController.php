<?php

namespace App\Http\Controllers\Api\Clients;

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
use App\Http\Resources\ClientsResource;
use App\Http\Resources\IndustryResource;
use App\Models\Industry;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:2',
                'email' => 'required|email|unique:clients,email|regex:/(.+)@(.+)\.(.+)/i',
                'phone' => 'required|min:10|max:14',
                'location' => 'required',
                'industry_id' => 'required|exists:industries,id',
                'user_id' => 'required|exists:users,id',
                'comments' => 'max:1000',
                'website_url' => 'min:3',
                'contact_information' => 'required'
            ]
        );

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

        $accountD = Client::query()->Where('name', 'ilike', "%$request->name%")->orWhere('email', 'ilike', "%$request->email%")->first();
        if ($accountD) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'Client already exists!',
                'recommendation' => 'Try Signing In',
            ]);
        }


        $client = new Client();
        $client->email = $credentials['email'];
        $client->name = $credentials['name'];
        $client->phone = $credentials['phone'];
        $client->location = $credentials['location'];
        $client->industry_id = $credentials['industry_id'];
        $client->user_id = $credentials['user_id'];
        $client->comments = $credentials['comments'];
        $client->website_url = $credentials['website_url'];
        $client->contact_information = json_encode($credentials['contact_information']);

        $client->save();

        if ($client) {
            $response  = [
                'error' => false,
                'message' => 'Successfully Created a New Account',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to create a new Account!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }



    public function view($id)
    {
        $data = Client::where('clients.user_id',$id)->select('clients.*', 'industries.name as industry')->join('industries', 'industries.id', '=', 'clients.industry_id')->get();
        if ($data) {
            $response  = [
                'data' => ClientsResource::collection($data),
                'error' => false,
                'message' => 'Successfully Fetched Accounts',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Accounts!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:clients,id',
        ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation' => 'Client Does Not Exists',
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $data = Client::where('clients.id', $id)->select('clients.*', 'industries.name as industry')->join('industries', 'industries.id', '=', 'clients.industry_id')->get();
        if ($data) {
            $response  = [
                'data' => ClientsResource::collection($data),
                'error' => false,
                'message' => 'Successfully Fetched Account Data',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Account Data!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function delete($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:clients,id',
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
            $delete = Client::findOrFail($id);
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

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:clients,id',
                'name' => 'required|min:2',
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
                'phone' => 'required|min:10|max:14',
                'location' => 'required',
                'industry_id' => 'required|exists:industries,id',
                'user_id' => 'required|exists:users,id',
                'website_url' => 'min:3',
                'comments' => 'max:1000',
                'contact_information' => 'required'
            ]
        );

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

        $update = Client::where('id', $request->id)->update([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'phone' => $credentials['phone'],
            'location' => $credentials['location'],
            'industry_id' => $credentials['industry_id'],
            'user_id' => $credentials['user_id'],
            'website_url' => $credentials['website_url'],
            'comments' => $credentials['comments'],
            'contact_information' => json_encode($credentials['contact_information']),
        ]);

        if ($update) {
            $response  = [
                'error' => false,
                'message' => 'Successfully Updated Account Details',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to Update Account Data!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function industries()
    {
        $data = Industry::all();
        if ($data) {
            $response  = [
                'data' => IndustryResource::collection($data),
                'error' => false,
                'message' => 'Successfully Fetched Sectors',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to fetch Sectors Data!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function createindustry(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:2|unique:industries,name',
            ]
        );

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

        $industry = new Industry();
        $industry->name = $credentials['name'];
        $industry->save();

        if ($industry) {
            $response  = [
                'error' => false,
                'message' => 'Successfully Created New Sector',
            ];
            return response()->json($response, 200);
        } else {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to Create new Sector!',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }
}
