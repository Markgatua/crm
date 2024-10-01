<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Account;
use App\Models\Solution;
use App\Models\ClientType;
use App\Models\User;
use App\Models\ProjectStage;
use App\Models\SolutionSubType;
use App\Models\Industry;
use App\Models\SolutionType;
use App\Models\ProjectStageInformation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CRMController extends Controller
{
    public function index(){
        $clients = Client::with('industry')->get();
        $industries = Industry::all();
        $existingclients = Client::with('industry')->where('is_existing',1)->get();
        return Inertia::render('CRM/index',[
            'industries' => $industries,
            'clients' => $clients,
            'existingclients'=>$existingclients
        ]);
    }

    public function renewals(){

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

        ///////////////
        //////////////
        if($request->id == null){
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
            $client->is_existing = 1;
            $client->saveOrFail();

            return back()->with('success', 'Account Created Successfully');
        } catch (\Exception $e) {
            // Handle the exception if saveOrFail fails
            return back()->withErrors('error', 'Failed to create account. Please try again.');
        }
        }else{
            try {
            $client = Client::find($request->id);
            $client->is_existing = 1;
            $client->saveOrFail();
            }  catch(\Exception $e){
                return back()->withErrors('error', 'Failed to Push Account to CRM. Please try again.');
            }
        }
    }

    public function business($id){
        $user_id = Auth::user()->id;
        $clienttypes = ClientType::all();
        $solutiontypes = SolutionType::all();
        $solutions = Solution::all();
        $solutionsubtypes = SolutionSubType::all();


        $accounts = Account::with(['client', 'latestStage.projectStage'])
            ->where('client_id', $id)->where('crm',1)
            ->select('accounts.*', 'solution_types.solution_type_name', 'solutions.solution_name', 'solution_sub_types.solution_sub_type_name')
            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
            ->get();



        // $data2 = ProjectStageInformation::where('account_id',)


        $projectstages = ProjectStage::all();
        $client = Client::where('id', $id)->first();
        $presales = User::where('role_id',6)->where('is_active', 1)->get();
        return Inertia::render('CRM/Businesses/index',[
            'client' => $client,
            'accounts' => $accounts,
            'clienttypes'=> $clienttypes,
            'solutiontypes' => $solutiontypes,
            'solutions'=> $solutions,
            'solutionsubtypes' => $solutionsubtypes,
            'projectstages' => $projectstages,
            'presales' => $presales
        ]);
    }


    public function createbusiness(Request $request){
        // $rules = [
        //     'client_type_id' => 'required|exists:client_types,id',
        //     'project_stage_id' => 'required|exists:project_stages,id',
        //     // 'documents' => 'array',
        //     // 'documents.*.name' => 'string|required',
        //     // 'documents.*.file' => 'file|required',
        // ];

        // // Create a validator instance and validate the request
        // $validator = Validator::make($request->all(), $rules);
        // // $validatedData = $request->validate($rules);

        // // Check if validation fails
        // if ($validator->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }


        $client = Client::where('id',$request->client_id)->first();
        if($request->useaccountcontacts == true){
            $cinfo = json_decode($client->contact_information);
            if (!empty($request->input('contact_information'))) {
                // Merge the existing and new contact information
                $cinfo = array_merge($cinfo, $request->input('contact_information'));
            }
        }else{
            $cinfo = $request->input('contact_information');
        }

        $arrA = [];


        if ($request->file('documents')) {
            foreach ($request->file('documents') as $key => $document) {
                if (isset($document['file'])) {
                    $fileName = time() . '_' . $document['file']->getClientOriginalName();
                    $document['file']->move(public_path('uploads'), $fileName);
                        $file_path = '/uploads/'.$fileName;
                    array_push($arrA,["key"=>$request->input('documents')[$key]['name'],"value"=>$file_path]);
                }
            }
        }


        $meta = [];

        $fields = [
            "Next Action" => $request->next_actions,
            "Product Category" => $request->solution_name,
            "Expected Sale Value" => $request->expected_sale_value,
            "Projected Month Of Closure" => $request->projected_month_of_closure,
            "Closure Probability" => $request->probability_of_closure,
            "Estimated Month Of Closure" => $request->expected_month_of_closure,
            "Deal Amount" => $request->deal_amount,
            "Margins Projection" => $request->margins_projection,
            "Project Delivery Date" => $request->project_delivery_date,
            "Reasons For Loosing" => $request->reason_for_losing,
            "Presales Assigned" => $request->presale_assigned,
            "Site Surveys" => $request->site_survey_comments,
            "Due Date" => $request->site_survey_due_date,
            "Reasons For Overdue" => $request->reason_for_overdue,
            "Next Steps" => $request->overdue_next_steps,
            "contract_name" => $request->contract_name,
            "contract_number" => $request->contract_number,
            "contract_validity" => $request->contract_validity,
            "contract_start_date" => $request->contract_start_date,
            "contract_end_date" => $request->contract_end_date,
            "contract_license" => $request->contract_license,
            "contract_license_type" => $request->contract_license_type,
            "contract_license_renewal_validity" => $request->contract_license_renewal_validity,
            "contract_license_renewal_start_date" => $request->contract_license_renewal_start_date,
            "contract_license_renewal_end_date" => $request->contract_license_renewal_end_date,
        ];

        foreach ($fields as $key => $value) {
            if ($value !== null && $value !== '') {
                if($value == -1){

                }else{
                    $meta[] = ["key" => $key, "value" => $value];
                }
            }
        }

        // Add Documents key with array of file paths if there are any documents
        if (!empty($arrA)) {
            $meta[] = ["key" => "Documents", "value" => $arrA];
        }

        try {
            DB::transaction(function () use ($request,$client,$meta,$cinfo) {

            // Create a new client instance and save the validated data
            $account = new Account();
            if($request->input('solution_name') != null && $request->input('solution_name') != ''){
                $account->business_name = $client->name. ' - '. $request->input('solution_name');
            }else{
            $account->business_name = $client->name;
            }

            $account->solution_name = $request->input('solution_name');
            $account->client_id = $request->client_id;
            $account->user_id = Auth::user()->id;
            $account->client_type_id = $request->input('client_type_id');
            $account->solution_type_id = $request->input('solution_type_id');
            $account->solution_id = $request->input('solution_id');
            $account->solution_sub_type_id = $request->input('solution_sub_type_id');
            $account->contact_information = json_encode($cinfo);
            $account->crm = 1;
            $account->save();

            // if($request->input('project_stage_id')== 10){

                $client = Client::find($request->client_id);
                $client->is_existing = 1;
                $client->saveOrFail();
            // }

            $todaydate = Carbon::now()->format('F j, Y');

            $projectinfo = new ProjectStageInformation();
            $projectinfo->project_stage_id = 10;
            $projectinfo->account_id = $account->id;
            $projectinfo->stage_information = "Account updated on $todaydate to stage $request->project_stage_id";
            $projectinfo->meta = json_encode($meta);
            $projectinfo->save();

            return back()->with('success', 'Account Created Successfully');
        });
        } catch (\Exception $e) {
            // Handle the exception if saveOrFail fails
            return back()->with('error','Failed to create account. Please try again.');
        }
    }
}
