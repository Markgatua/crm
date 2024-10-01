<?php

namespace App\Http\Controllers\Api\Revenue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Utils\RespondWithError;
use App\Actions\Utils\RespondWithSuccess;
use App\Http\Resources\DocumentTypeResource;
use App\Models\DocumentType;
use App\Models\ProjectStageInformation;
use App\Models\Account;
use App\Models\Revenue;


class RevenueController extends Controller
{
    public function DocumentTypes(){
        $data = DocumentType::all();
        if($data){
            $response  = [
                'data' => DocumentTypeResource::collection($data),
                'error' => false,
                'message' => 'Successfully Fetched Document Types',
            ];
            return response()->json($response, 200);
            }else{
                return RespondWithError::run(...[
                    'status' => 403,
                    'code' => RespondWithError::$ERROR_CODE_BAD_REQUEST,
                    'message' => 'Failed to fetch Document Types Data!',
                    'recommendation' => 'Try Again, if the issue persists contact Admin',
                ]);
            }
    }

    public function Summary($id){
        $arrA = [];
        $totalAccounts = Account::where('user_id',$id)->count();
        $accounts = Account::where('user_id',$id)->get();
        foreach($accounts as $account){
            array_push($arrA,$account->id);
        }
        $totalClosedAccounts = ProjectStageInformation::wherein('account_id',$arrA)->where('project_stage_id',5)->count();
        $totalAmount = Revenue::whereIn('account_id', $arrA)->selectRaw('SUM(amount) as total_amount')->first()->total_amount;
        $totalOpenAccounts = intval($totalAccounts) - intval($totalClosedAccounts);
        $response  = [
            'message' => 'Fetched Reports Data Successfully',
            'data' => [
                "Total Accounts" => $totalAccounts,
                "Total Sales" => $totalAmount == null ? 0 : $totalAmount,
                "Total Closed Accounts" => $totalClosedAccounts,
                "Total Open Accounts" => $totalOpenAccounts
            ]
        ];
        return response()->json($response, 200);
    }
}
