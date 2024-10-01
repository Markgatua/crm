<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Actions\Utils\RespondWithError;
use App\Actions\Utils\RespondWithSuccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\EmailVerificationToken;
use Carbon\Carbon;
use App\Helpers\EmailsHelper;
use App\Helpers\SmsesHelper;
use App\Models\EmailPasswordResetCode;
use App\Models\PhoneVerificationToken;
use App\Models\PhonePasswordResetCode;
use App\Http\Resources\Auth\UsersAuthResource;


class AuthController extends Controller
{
    //******************************************************************************************/
    //******************************************************************************************/
    //******************************** START OF EMAIL AUTH *************************************/
    //******************************************************************************************/
    //******************************************************************************************/

    public function registerwithemail(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|unique:users,email|regex:/(.+)@(.+)\.(.+)/i',
                'password' => [
                    'required',
                    'min:8',
                    'max:12',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).+$/'
                ],
                'firstname' => 'required|min:2',
                'lastname' => 'required|min:2'
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

        $user = User::query()->where(['email' => $credentials['email']])->first();
        if ($user) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'User already exists!',
                'recommendation' => 'Try Signing In',
            ]);
        }

        $user = new User();
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->firstname = $credentials['firstname'];
        $user->lastname = $credentials['lastname'];
        $user->save();

        if ($user == null) {
            return response()->json(["error" => true, "message" => "Error registering user", "recommendation" => "If Error Persists, kindly Contact your Admin"], 422);
        }

        $otp_code = rand(10000, 90000);
        $email_verification_token = EmailVerificationToken::where('email', $credentials['email'])->first();
        if (!$email_verification_token) {
            $email_verification_token = new EmailVerificationToken();
        }
        $minutes = env('MAIL_VALIDITY');
        $validity = Carbon::now('EAT')->addMinutes($minutes)->format('H:i:s');
        $email_verification_token->email = $credentials['email'];
        $email_verification_token->token = $otp_code;
        $email_verification_token->validuntil = $validity;
        $email_verification_token->created_at = Carbon::now();
        $email_verification_token->save();

        try {
            EmailsHelper::emailverificationmailone($credentials['email'],$otp_code);

        } catch (\Exception $e) {
            //the system will take the user to code entry screen, there they will be able to enter the code,
            //incase the code wasnt sent, there will be an option to resend the code again and again ,
            //hence there is no need to send error here incase there is a failure
            //in this code handled by subsequentemailverification function
        }

        $response  = [
            'accountemail' => User::where('email', $credentials['email'])->first('email'), //make sure to store this email in sharedpreferences in flutter or cache it in other frontend languages
            'message' => 'Verification code sent to your email',
            'is_verified' => false // unauthorize if this value is false
        ];
        return response()->json($response, 200);
    }

    public function subsequentemailverification(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
            ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                "payload" => $validator->errors()->toArray(),
                'recommendations' => 'Fill the required Email field Correctly while Observing Rules'
            ]);
        }
        $credentials = $validator->validated();
        $user = User::query()->where(['email' => $credentials['email']])->first();
        if (!$user) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'Not a User',
                'recommendation' => 'Sign Up an account'
            ]);
        }else{
        if($user->email_verified_at !=  null){
            $response  = [
                'message' => 'Your Email is already Verified',
                'recommendation' => 'Kindly Sign In/ Login',
                'is_verified' => $user->email_verified_at != null ? true : false,
            ];
            return response()->json($response, 403);
        }
        $otp_code = rand(10000, 90000);

        $email_verification_data = EmailVerificationToken::where('email', $credentials['email'])->first();
        if (!$email_verification_data) {
            $email_verification_data = new EmailVerificationToken();
        }else{
            $email_verification_data->delete();
        }
        $minutes = (int) env("MAIL_VALIDITY");
        $validity = Carbon::now('EAT')->addMinutes($minutes)->format('H:i:s');
        $email_verification_data->email = $credentials['email'];
        $email_verification_data->token = $otp_code;
        $email_verification_data->validuntil = $validity;
        $email_verification_data->created_at = Carbon::now();
        $email_verification_data->save();

        try {
            EmailsHelper::emailverificationmailone($credentials['email'],$otp_code);
        } catch (\Exception $e) { 
            // return response()->json($e, 405);
        }


        $response  = [
            'message' => 'Another Verification Code has been sent  to your email',
            'is_verified' => $user->emailisverified != 0 ? true : false, // unauthorize if this value is false
        ];
        return response()->json($response, 200);
      }
    }

    public function verifyemail(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
                'verificationcode' => 'required|min:5|max:5',//min depends on generated token size eg loc 58 =>loc line of code
            ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                "payload" => $validator->errors()->toArray(),
                'recommendation' => 'Fill in the required fields Correctly while Observing Rules'
            ]);
        }
        $credentials = $validator->validated();
        $user = User::where('email',$credentials['email'])->first();
        if($user == null){
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'User does not exist!',
                'recommendation' =>"Consider Signing Up"
            ]);
        }
        if($user->emailisverified == 1){
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'Email is already Verified!',
                'recommendation' =>"Try Signing In / Login"
            ]);
        }
        $email_verification_data = EmailVerificationToken::where('email', $credentials['email'])->first();

        $timenow = Carbon::now('EAT')->format('H:i:s');
        if($email_verification_data == null){
            return response()->json(["error" => true, "message" => "Verification Code Mismatch.", "recommendation"=>"Hmm... Request For another Verification code."], 422);

        }else{
            if($credentials['verificationcode'] == $email_verification_data->token && $email_verification_data->validuntil >= $timenow){
                
               $user->query()->update(['email_verified_at' => Carbon::now()]);
               
                if($user->is_active == 1){
                
                $response  = [
                    'message' => 'Email Verified Successfully',
                    'is_verified' => true, // unauthorize if this value is false
                    'recommedation' => 'Redirect User to Login Screen'  //make sure to store this email in sharedpreferences in flutter or cache it in other frontend languages
                ];
                return response()->json($response, 200);
                }else{
                    return RespondWithError::run(...[
                        'status' => 403,
                        'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                        'message' => 'Account Deactivated!',
                        'recommendation' =>"Consult Admin"
                    ]);
                }
            }elseif($email_verification_data->validuntil < $timenow){
                return response()->json(["error" => true, "message" => "Verification Code Expired.", "recommendation"=>"Hmm... Request For another Verification code."], 422);
            }else{
                return response()->json(["error" => true, "message" => "Wrong Verification Code.", "recommendation"=>"Hmm... Enter the Correct Verification Code."], 422);
            }
        }

    }

    public function loginwithemail(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
                'password' => 'required'
            ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                'recommendation'=>"Fill in the required fields Correctly while Observing Rules",
                "payload" => $validator->errors()->toArray()
            ]);
        }
        $credentials = $validator->validated();

        $user = User::query()->select('users.*','departments.name AS department','roles.name AS role')
        ->join('departments','departments.id','=','users.department_id')
        ->join('roles','roles.id','=','users.role_id')
        ->where(['email' => $credentials['email']])->first();
        if ($user == null) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'User does not exist!',
                'recommendation'=>'Consider Signing Up'
            ]);
        }elseif ($user != null && $user->is_active == 0) {
            return response()->json([
                'error' => true,
                'message' => 'Your account has been deactived',
                'recommendation' => "Consult with Admin"
            ], 401);
        }elseif($user != null && $user->email_verified_at == null){
            return response()->json([
                'error' => true,
                'message' => 'Email is not Verified',
                'recommendation' => "Please Verify your Email"
            ], 401);
        }else{
            if (!Hash::check($credentials['password'],$user->password)) {

                return response()->json([
                    'error' => true,
                    'message' => 'Invalid credentials',
                    'recommendation' => "Fill in the required fields Correctly while Observing Rules"
                ], 401);
            }
            $token = $user->createToken('authToken')->plainTextToken;
            $response  = [
                'user' => $user,
                'is_verified' => $user->email_verified_at != null ? true : false,
                'token' => $token
            ];
            return response()->json($response, 200);
        }
    }

    //******************************************************************************************/
    //******************************************************************************************/
    //***************************** START OF PASSWORD RECOVERY *********************************/
    //******************************************************************************************/
    //******************************************************************************************/

    public function passwordresetrequestviaemail(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
            ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                "payload" => $validator->errors()->toArray(),
                'recommendation' => 'Fill in the required fields Correctly while Observing Rules'
            ]);
        }
        $credentials = $validator->validated();

        $user = User::query()->where(['email' => $credentials['email']])->first();
        if ($user == null) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'User does not exist!',
                'recommendation' => "Consider Signing Up"
            ]);
        }else{
            $otp_code = rand(10000, 90000);

            $email_verification_data = EmailPasswordResetCode::where('email', $credentials['email'])->first();
            if (!$email_verification_data) {
                $email_verification_data = new EmailPasswordResetCode();
            }else{
                $email_verification_data->delete();
            }
            $minutes = (int) env("MAIL_VALIDITY");
            $validity = Carbon::now('EAT')->addMinutes($minutes)->format('H:i:s');
            $email_verification_data->email = $credentials['email'];
            $email_verification_data->token = $otp_code;
            $email_verification_data->validuntil = $validity;
            $email_verification_data->created_at = Carbon::now();
            $email_verification_data->save();

            try {
                EmailsHelper::emailpasswordresetmail($credentials['email'],$otp_code);
            } catch (\Exception $e) { }

            $response  = [
                'accountemail' =>$credentials["email"], //store in sharedpreferences
                'message' => 'Password Reset Code has been sent  to your email',
                'is_verified' => $user->email_verified_at != null ? true : false, // unauthorize if this value is false
            ];
            return response()->json($response, 200);
        }
    }

    public function passwordresetviaemail(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
                'resetcode' => 'required|min:5|max:5',
                'password' => [
                    'required',
                    'min:8',
                    'max:12',
                    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).+$/'
                ]
            ]);
        if ($validator->fails()) {
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => $validator->errors()->first(),
                "payload" => $validator->errors()->toArray(),
                "recommendation" => "Fill in the required fields Correctly while Observing Rules"
            ]);
        }
        $credentials = $validator->validated();
        $user = User::where('email',$credentials['email'])->first();

        if($user == null){
            return RespondWithError::run(...[
                'status' => 403,
                'code' => RespondWithError::$ERROR_CODE_ACCESS_DENIED,
                'message' => 'User does not exist!',
                'recommendation' => "Consider Signing Up"
            ]);
        }
        
        $email_verification_data = EmailPasswordResetCode::where('email', $credentials['email'])->first();

        $timenow = Carbon::now('EAT')->format('H:i:s');
        if($email_verification_data == null){
            return response()->json(["error" => true, "message" => "Verification Code Mismatch.", "recommendation"=>"Hmm... Request For another Verification code."], 422);

        }else{
            if($credentials['resetcode'] == $email_verification_data->token && $email_verification_data->validuntil >= $timenow){

                $user->query()->update(['password'=>Hash::make($credentials['password'])]);
                
                if($user->is_active == 1){

                $response  = [
                    'message' => 'Password Updated Successfully',
                    'is_verified' => $user->email_verified_at != null ? true : false, // unauthorize if this value is false
                    'recommedation' => "Redirect user to login screen" //
                ];
                return response()->json($response, 200);
                }else{
                    return response()->json([
                        'error' => true,
                        'message' => 'Your account has been deactived',
                        'recommendation' => "Consult with Admin"
                    ], 401);
                }
            }elseif($email_verification_data->validuntil < $timenow){
                return response()->json(["error" => true, "message" => "Password Reset Code Expired.", "recommendation"=>"Hmm... Request For another Password Reset code."], 422);
            }else{
                return response()->json(["error" => true, "message" => "Wrong Password Reset Code.", "recommendation"=>"Hmm... Enter the Correct Password Reset Code."], 422);
            }
        }        
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
