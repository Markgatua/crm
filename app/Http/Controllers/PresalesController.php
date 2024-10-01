<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\ProjectStageInformation;


class PresalesController extends Controller
{
    public function scoping(){
        $accounts = Account::with(['client', 'latestStage.projectStage'])

        ->where('accounts.stage', 2)
        ->whereHas('latestStage.projectStage', function($query) {
            $query->where('project_stage_id', 2);
        })
        ->select('accounts.*', 'solution_types.solution_type_name', 'solutions.solution_name', 'solution_sub_types.solution_sub_type_name')
        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
        ->get();
        return Inertia::render('Presales/Scoping',[
            'accounts' => $accounts,
        ]);
    }

    public function updateStage(Request $request){

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
            "Presale description" => $request->presale_description,
            "Presale Quotation" => $request->presale_quotation,
        ];

        foreach ($fields as $key => $value) {
           $meta[] = ["key" => $key, "value" => $value];
        }

        // Add Documents key with array of file paths if there are any documents
        if (!empty($arrA)) {
            $meta[] = ["key" => "Documents", "value" => $arrA];
        }


            $todaydate = Carbon::now()->format('F j, Y');

            $projectinfo = new ProjectStageInformation();
            $projectinfo->project_stage_id = $request->input('project_stage_id');
            $projectinfo->account_id = $request->account_id;
            $projectinfo->stage_information = "Account updated on $todaydate to stage $request->project_stage_id";
            $projectinfo->meta = $meta;
            $projectinfo->saveOrFail();

            return back()->with('success', 'Account Stage Updated Successfully');


    }

    public function tenderOrRFQ(){
        $accounts = Account::with(['client', 'latestStage.projectStage'])
        // ->where('accounts.stage', 7)
        ->whereHas('latestStage.projectStage', function($query) {
            $query->where('project_stage_id', 7);
        })
        ->select('accounts.*', 'solution_types.solution_type_name', 'solutions.solution_name', 'solution_sub_types.solution_sub_type_name')
        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
        ->get();
        return Inertia::render('Presales/TenderOrRFQ',[
            'accounts' => $accounts,
        ]);
    }
}
