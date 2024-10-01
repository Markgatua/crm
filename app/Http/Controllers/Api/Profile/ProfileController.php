<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
// use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Actions\Utils\RespondWithError;
use App\Actions\Utils\RespondWithSuccess;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    public function UpdatePassword(Request $request){
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|exists:users,id',
                'password' => [
                    'required',
                    'min:8',
                    'max:20',
                    `regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,12}$/`
                ],
                'old_password' => 'required'
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

        $user = User::findOrFail($credentials['id']);

        if (!Hash::check($credentials['old_password'], $user->password)) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'The old password is incorrect',
                'recommendation' => 'Please provide the correct old password'

            ]);

        }

        // Hash the new password and update the user's password in the database
        $user->password = Hash::make($credentials['password']);
        $user->save();

        $response  = [
            'message' => 'Password Updated Successfully',
            'is_verified' => $user->emailisverified != 0 ? true : false, // unauthorize if this value is false
        ];
        return response()->json($response, 200);
    }

    public function UpdateProfile(Request $request){
        $validator = Validator::make($request->all(),
            [
                'id' => 'required|exists:users,id',
                'firstname' => 'required|min:2',
                'lastname' => 'required|min:2',
                'designation' => 'required',
                'phone' => 'required','min:10','max:10',Rule::unique('users','phone')->ignore(Auth::user()->id),
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

        $update = User::where('id',$credentials['id'])->update(['first_name'=>$credentials['firstname'],'last_name'=>$credentials['lastname'],'phone'=>$credentials['phone'],'designation'=>$credentials['designation']]);
        if($update){
            $newuser = User::where('id',$credentials['id'])->first();
            $response  = [
                'message' => 'Profile Updated Successfully',
                'user' => $newuser,
            ];
            return response()->json($response, 200);
        }else{
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                'message' => 'Failed to Update Profile',
                'recommendation' => 'Try Again, if the issue persists contact Admin',
            ]);
        }
    }

    public function analytics($id){
        $data1 = Account::where('user_id', $id)->where('client_type_id',1)->count();
        $data2 = Account::where('user_id', $id)->where('client_type_id',2)->count();
        $data3 = Account::where('user_id', $id)->where('client_type_id',3)->count();

        $response  = [
            'message' => 'Fetched Analytics Data Successfully',
            'data' => [
                "New Clients" => $data1,
                "Upselling Clients" => $data2,
                "Renewal Clients" => $data3
            ]
        ];
        return response()->json($response, 200);
    }
}
