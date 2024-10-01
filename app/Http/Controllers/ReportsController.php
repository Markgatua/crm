<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ProjectStage;
use App\Models\ProjectStageInformation;
use App\Models\Solution;
use App\Models\SolutionSubType;
use App\Models\SolutionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Industry;
use Inertia\Inertia;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function sheet(){
        $data = User::select(
            'users.id',
            'users.first_name',
            'users.last_name',
            DB::raw('COUNT(accounts.id) as accounts_count'),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 1 THEN accounts.business_name ELSE NULL END, '||') as prospects_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 2 THEN accounts.business_name ELSE NULL END, '||') as scooping_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 3 THEN accounts.business_name ELSE NULL END, '||') as evaluation_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 4 THEN accounts.business_name ELSE NULL END, '||') as approval_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 5 THEN accounts.business_name ELSE NULL END, '||') as closed_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 6 THEN accounts.business_name ELSE NULL END, '||') as lost_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 7 THEN accounts.business_name ELSE NULL END, '||') as presales_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 8 THEN accounts.business_name ELSE NULL END, '||') as projects_business_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 9 THEN accounts.business_name ELSE NULL END, '||') as overdue_business_names"),

            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 1 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 1) as prospects_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 2 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 2) as scooping_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 3 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 3) as evaluation_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 4 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 4) as approval_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 5 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 5) as closed_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 6 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 6) as lost_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 7 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 7) as presales_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 8 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 8) as projects_contact_informations"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 9 THEN clients.contact_information ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 9) as overdue_contact_informations"),

            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 1 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 1) as prospects_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 2 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 2) as scooping_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 3 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 3) as evaluation_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 4 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 4) as approval_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 5 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 5) as closed_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 6 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 6) as lost_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 7 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 7) as presales_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 8 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 8) as projects_metas"),
            DB::raw("jsonb_agg(CASE WHEN latest_stage.project_stage_id = 9 THEN latest_stage.meta ELSE null END) FILTER (WHERE latest_stage.project_stage_id = 9) as overdue_metas"),


            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 2 THEN accounts.contact_information ELSE [] END, '||') as scooping_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 3 THEN accounts.contact_information ELSE [] END, '||') as evaluation_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 4 THEN accounts.contact_information ELSE [] END, '||') as approval_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 5 THEN accounts.contact_information ELSE [] END, '||') as closed_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 6 THEN accounts.contact_information ELSE [] END, '||') as lost_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 7 THEN accounts.contact_information ELSE [] END, '||') as presales_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 8 THEN accounts.contact_information ELSE [] END, '||') as projects_contact_informations"),
            // DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 9 THEN accounts.contact_information ELSE [] END, '||') as overdue_contact_informations"),

            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 1 THEN clients.name ELSE NULL END, '||') as prospects_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 2 THEN clients.name ELSE NULL END, '||') as scooping_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 3 THEN clients.name ELSE NULL END, '||') as evaluation_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 4 THEN clients.name ELSE NULL END, '||') as approval_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 5 THEN clients.name ELSE NULL END, '||') as closed_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 6 THEN clients.name ELSE NULL END, '||') as lost_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 7 THEN clients.name ELSE NULL END, '||') as presales_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 8 THEN clients.name ELSE NULL END, '||') as projects_client_names"),
            DB::raw("string_agg(CASE WHEN latest_stage.project_stage_id = 9 THEN clients.name ELSE NULL END, '||') as overdue_client_names"),

            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
        )
        ->join('accounts', 'accounts.user_id', '=', 'users.id')
        ->join('clients', 'clients.id', '=', 'accounts.client_id')
        ->leftJoin(
            DB::raw('(
                SELECT
                    psi.account_id,
                    MAX(psi.project_stage_id) as project_stage_id,
                    jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                    jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                FROM project_stage_information psi
                LEFT JOIN accounts a ON a.id = psi.account_id
                LEFT JOIN clients c ON c.id = a.client_id
                GROUP BY psi.account_id
            ) as latest_stage'),
            'latest_stage.account_id', '=', 'accounts.id'
        )
        ->groupBy('users.id', 'users.first_name', 'users.last_name')
        ->orderBy('accounts_count', 'desc')
        ->get();


        // Initialize totals
        $totalProspectsCount = 0;
        $totalScoopingCount = 0;
        $totalEvaluationCount = 0;
        $totalApprovalCount = 0;
        $totalClosedCount = 0;
        $totalLostCount = 0;
        $totalPresalesCount = 0;
        $totalProjectsCount = 0;
        $totalOverdueCount = 0;

        // Initialize stages array
        $stages = [
            'prospects' => [],
            'scoping' => [],
            'evaluation' => [],
            'approval' => [],
            'closed' => [],
            'lost' => [],
            'presales' => [],
            'projects' => [],
            'overdue' => [],
        ];

        // Populate stages array
        foreach ($data as $user) {
            $stages['prospects'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->prospects_business_names,
                'count' => $user->prospects_count,
                'clients' => $user->prospects_client_names,
                'contact_informations' => $user->prospects_contact_informations,
                'metas' => $user->prospects_metas,
            ];

            $stages['scoping'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->scooping_business_names,
                'count' => $user->scooping_count,
                'clients' => $user->scooping_client_names,
                'contact_informations' => $user->scooping_contact_informations,
                'metas' => $user->scooping_metas,
            ];

            $stages['evaluation'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->evaluation_business_names,
                'count' => $user->evaluation_count,
                'clients' => $user->evaluation_client_names,
                'contact_informations' => $user->evaluation_contact_informations,
                'metas' => $user->evaluation_metas,
            ];

            $stages['approval'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->approval_business_names,
                'count' => $user->approval_count,
                'clients' => $user->approval_client_names,
                'contact_informations' => $user->approval_contact_informations,
                'metas' => $user->approval_metas,
            ];

            $stages['closed'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->closed_business_names,
                'count' => $user->closed_count,
                'clients' => $user->closed_client_names,
                'contact_informations' => $user->closed_contact_informations,
                'metas' => $user->closed_metas,
            ];

            $stages['lost'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->lost_business_names,
                'count' => $user->lost_count,
                'clients' => $user->lost_client_names,
                'contact_informations' => $user->lost_contact_informations,
                'metas' => $user->lost_metas,
            ];

            $stages['presales'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->presales_business_names,
                'count' => $user->presales_count,
                'clients' => $user->presales_client_names,
                'contact_informations' => $user->presales_contact_informations,
                'metas' => $user->presales_metas,
            ];

            $stages['projects'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->projects_business_names,
                'count' => $user->projects_count,
                'clients' => $user->projects_client_names,
                'contact_informations' => $user->projects_contact_informations,
                'metas' => $user->projects_metas,
            ];

            $stages['overdue'][] = [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'business_names' => $user->overdue_business_names,
                'count' => $user->overdue_count,
                'clients' => $user->overdue_client_names,
                'contact_informations' => $user->overdue_contact_informations,
                'metas' => $user->overdue_metas,
            ];
        }

        // Calculate totals
        foreach ($data as $user) {
            $totalProspectsCount += $user->prospects_count;
            $totalScoopingCount += $user->scooping_count;
            $totalEvaluationCount += $user->evaluation_count;
            $totalApprovalCount += $user->approval_count;
            $totalClosedCount += $user->closed_count;
            $totalLostCount += $user->lost_count;
            $totalPresalesCount += $user->presales_count;
            $totalProjectsCount += $user->projects_count;
            $totalOverdueCount += $user->overdue_count;
        }

        $total = Account::count();

        $totals = [
            'total' => $total,
            'prospects' => $totalProspectsCount,
            'scooping' => $totalScoopingCount,
            'evaluation' => $totalEvaluationCount,
            'approval' => $totalApprovalCount,
            'closed' => $totalClosedCount,
            'lost' => $totalLostCount,
            'presales' => $totalPresalesCount,
            'projects' => $totalProjectsCount,
            'overdue' => $totalOverdueCount,
        ];


        $data2 = User::select(
            'users.id',
            'users.first_name',
            'users.last_name',
            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
        )
        ->join('accounts', 'accounts.user_id', '=', 'users.id')
        ->join('clients', 'clients.id', '=', 'accounts.client_id')
        ->leftJoin(
            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                      FROM project_stage_information
                      GROUP BY account_id) as latest_stage'),
            'latest_stage.account_id', '=', 'accounts.id'
        )
        ->groupBy('users.id', 'users.first_name', 'users.last_name')
        ->orderBy('accounts_count', 'desc')
        ->get();


        // return $data2;

        // return $stages;


        $datah = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)->select('accounts.* as accountsdata', 'project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation',
        'clients.website_url as clientwebsiteurl','clients.contact_information as mainclienccontactinformation',
        'solution_types.solution_type_name', 'solutions.solution_name', 'solution_sub_types.solution_sub_type_name')
            ->join(DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                            FROM project_stage_information
                            GROUP BY account_id) as latest_stage'),
                function($join) {
                    $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                        ->on('project_stage_information.project_stage_id', '=', 'latest_stage.project_stage_id');
                })
            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
            ->join('users','users.id','=','accounts.user_id')
            ->leftjoin('client_types','client_types.id','=','accounts.client_type_id')
            ->join('clients','clients.id','=','accounts.client_id')
            ->get();

            $dataj = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)->select('accounts.* as accountsdata', 'project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation',
                'clients.website_url as clientwebsiteurl','clients.contact_information as mainclienccontactinformation',
                'solution_types.solution_type_name', 'solutions.solution_name', 'solution_sub_types.solution_sub_type_name')
                    ->join(DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                    FROM project_stage_information
                                    GROUP BY account_id) as latest_stage'),
                        function($join) {
                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                ->on('project_stage_information.project_stage_id', '=', 'latest_stage.project_stage_id');
                        })
                    ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                    ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                    ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                    ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                    ->join('users','users.id','=','accounts.user_id')
                    ->leftjoin('client_types','client_types.id','=','accounts.client_type_id')
                    ->join('clients','clients.id','=','accounts.client_id')
                    ->get();

        // $myArray = [];
        // foreach ($datah as $data) {
        //     $decodedMeta = json_decode($data->meta, true); // Decoding as an associative array
        //     if (is_array($decodedMeta)) {
        //         $myArray = array_merge($myArray, $decodedMeta); // Merge decoded meta into $myArray
        //     }
        // }

        // return $datah; // If you intended to return $myArray instead, return $myArray instead of $datah


        return Inertia::render('Reports/sheet', [
            'accounts' => $stages,
            'totals' => $totals,
            'counts' => $data2,
            'closedaccountsdata' => $datah,
            'evaluationaccountsdata' => $dataj,
        ]);
    }

    public function customreportpage(){
        $accountmanagers = User::where('sales_rep', true)->get();
        $stages = ProjectStage::get();
        $solutiontypes = SolutionType::get();
        $solutions = Solution::get();
        $solutionsubtypes = SolutionSubType::get();
        $sectors = Industry::all();

        return Inertia::render('Reports/custom', [
            'stages' => $stages,
            'accountmanagers' => $accountmanagers,
            'solutiontypes' => $solutiontypes,
            'solutions' => $solutions,
            'solutionsubtypes' => $solutionsubtypes,
            'sectors' => $sectors,
        ]);
    }

    public function mycustomreportpage(){
        $stages = ProjectStage::get();
        $solutiontypes = SolutionType::get();
        $solutions = Solution::get();
        $solutionsubtypes = SolutionSubType::get();
        $sectors = Industry::all();

        return Inertia::render('Reports/mycustom', [
            'stages' => $stages,
            'solutiontypes' => $solutiontypes,
            'solutions' => $solutions,
            'solutionsubtypes' => $solutionsubtypes,
            'sectors' => $sectors,
            'accountmanagers' => Auth::user()->id
        ]);
    }

    public function customreport(Request $request){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $account_manager = $request->accountmanager;
        $stages = $request['stages'];
        $solution_type = $request->solution_type_id;
        $solution = $request->solution_id;
        $solution_sub_type = $request->solution_subtype_id;
        $sector_id = $request->sector_id;

        if($account_manager != null){
            if($stages != []){
                $arrA = [];
                foreach($stages as $stage){
                    array_push($arrA, $stage['id']);
                }
                $arrAString = implode(',', $arrA);

                if($solution_type != null && $solution != null && $solution_sub_type != null){

                    // Convert the array to a comma-separated string

                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count')
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager)
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution)
                    ->where('accounts.solution_sub_type_id', $solution_sub_type)
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc');
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data->get();


                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager)
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc');
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2->get();


                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation->get();
                                        }else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed->get();

                                    }else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();
                                    }else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();

                                    }else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();
                                    }else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();
                                        }else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);


                }elseif($solution_type != null && $solution != null && $solution_sub_type == null){


                    // Convert the array to a comma-separated string

                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count')
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager)
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution)
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc');
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager)
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc');
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation->get();
                                        }else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed->get();
                                    }else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();

                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();

                                    }else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();
                                        }else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();

                                    }else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();

                                    }else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);



                }elseif($solution_type != null && $solution == null && $solution_sub_type == null){


                    // Convert the array to a comma-separated string

                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count')
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager)
                    ->where('accounts.solution_type_id', $solution_type)
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc');
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data->get();


                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager)
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc');
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation->get();
                                        }else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed->get();
                                    }else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();

                                    }else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();

                                    }else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();
                                        }else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();
                                        }else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);



                }elseif($solution_type == null && $solution == null && $solution_sub_type == null){


                    // Convert the array to a comma-separated string

                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count')
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')->get();


                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation->get();
                                        }else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed->get();
                                    }else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();
                                        }else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();
                                        }else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();
                                        }else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();
                                        }else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);



                }else{
                    //  return error
                }
            }else{
                if($solution_type != null && $solution != null && $solution_sub_type != null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager)
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution)
                    ->where('accounts.solution_sub_type_id', $solution_sub_type);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager)
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation->get();
                                        }else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed->get();
                                        }else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();
                                        }else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();
                                        }else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();
                                        }else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();
                                        }else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);

                }elseif($solution_type != null && $solution != null && $solution_sub_type == null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager)
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager)
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation->get();
                                        }else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed->get();
                                        }else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();
                                        }else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();
                                        }else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();
                                        }else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();
                                        }else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);
                }elseif($solution_type != null && $solution == null && $solution_sub_type == null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager)
                    ->where('accounts.solution_type_id', $solution_type);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager)
                                        ->where('accounts.solution_type_id', $solution_type);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);
                }elseif($solution_type == null && $solution == null && $solution_sub_type == null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('users.id', $account_manager);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('users.id',$account_manager);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();


                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();

                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();



                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();

                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();

                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects->get();

                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects->get();

                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost->get();

                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                                        ->where('users.id', $account_manager)
                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue->get();




                                return Inertia::render('Reports/accountmanager', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);
                }else{
                    //  return error
                }
            }
        }else{
            if($stages != []){
                $arrA = [];
                foreach($stages as $stage){
                    array_push($arrA, $stage['id']);
                }
                $arrAString = implode(',', $arrA);
                if($solution_type != null && $solution != null && $solution_sub_type != null){


                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution)
                    ->where('accounts.solution_sub_type_id', $solution_sub_type);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    LEFT JOIN accounts a ON a.id = psi.account_id
                                                    LEFT JOIN clients c ON c.id = a.client_id
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval
                                        ->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)
                                        ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);


                 }elseif($solution_type != null && $solution != null && $solution_sub_type == null){

                }
                if($solution_type != null && $solution != null && $solution_sub_type == null){



                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    LEFT JOIN accounts a ON a.id = psi.account_id
                                                    LEFT JOIN clients c ON c.id = a.client_id
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);


                }
                if($solution_type != null && $solution == null && $solution_sub_type == null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('accounts.solution_type_id', $solution_type);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data

                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    LEFT JOIN accounts a ON a.id = psi.account_id
                                                    LEFT JOIN clients c ON c.id = a.client_id
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('accounts.solution_type_id', $solution_type);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval
                                        ->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);


                 }elseif($solution_type != null && $solution != null && $solution_sub_type == null){


                }
                if($solution_type == null && $solution == null && $solution_sub_type == null){



                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.project_stage_id IN (' . $arrAString . ')
                            AND psi.created_at >= \'' . $startdate . '\'
                            AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    );

                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information psi
                                                    LEFT JOIN accounts a ON a.id = psi.account_id
                                                    LEFT JOIN clients c ON c.id = a.client_id
                                                    WHERE psi.project_stage_id IN (' . $arrAString . ')
                                                    AND psi.created_at >= \'' . $startdate . '\'
                                                    AND psi.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        );
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2

                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval
                                        ->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping
                                        ->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->whereIn('project_stage_information.project_stage_id', explode(',', $arrAString))

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);


                }
            }else{
                if($solution_type != null && $solution != null && $solution_sub_type != null){

                        $data = User::select(
                            'users.id',
                            'users.first_name',
                            'users.last_name',
                            DB::raw('COUNT(accounts.id) as accounts_count'),

                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                        )
                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                        ->leftJoin(
                            DB::raw('(
                                SELECT
                                    psi.account_id,
                                    MAX(psi.project_stage_id) as project_stage_id,
                                    jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                    jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                                FROM project_stage_information psi
                                LEFT JOIN accounts a ON a.id = psi.account_id
                                LEFT JOIN clients c ON c.id = a.client_id
                                WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                                GROUP BY psi.account_id
                            ) as latest_stage'),
                            'latest_stage.account_id', '=', 'accounts.id'
                        )
                        ->where('accounts.solution_type_id', $solution_type)
                        ->where('accounts.solution_id', $solution)
                        ->where('accounts.solution_sub_type_id', $solution_sub_type);
                        if ($sector_id != null) {
                            $data->where('clients.industry_id', $sector_id);
                        }
                        $data = $data
                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                        ->orderBy('accounts_count', 'desc')
                        ->get();

                        // Initialize totals
                        $totalProspectsCount = 0;
                        $totalScoopingCount = 0;
                        $totalEvaluationCount = 0;
                        $totalApprovalCount = 0;
                        $totalClosedCount = 0;
                        $totalLostCount = 0;
                        $totalPresalesCount = 0;
                        $totalProjectsCount = 0;
                        $totalOverdueCount = 0;

                        // Calculate totals
                        foreach ($data as $user) {
                            $totalProspectsCount += $user->prospects_count;
                            $totalScoopingCount += $user->scooping_count;
                            $totalEvaluationCount += $user->evaluation_count;
                            $totalApprovalCount += $user->approval_count;
                            $totalClosedCount += $user->closed_count;
                            $totalLostCount += $user->lost_count;
                            $totalPresalesCount += $user->presales_count;
                            $totalProjectsCount += $user->projects_count;
                            $totalOverdueCount += $user->overdue_count;
                        }

                        $total = Account::count();

                        $totals = [
                            'total' => $total,
                            'prospects' => $totalProspectsCount,
                            'scooping' => $totalScoopingCount,
                            'evaluation' => $totalEvaluationCount,
                            'approval' => $totalApprovalCount,
                            'closed' => $totalClosedCount,
                            'lost' => $totalLostCount,
                            'presales' => $totalPresalesCount,
                            'projects' => $totalProjectsCount,
                            'overdue' => $totalOverdueCount,
                        ];


                                            $data2 = User::select(
                                                'users.id',
                                                'users.first_name',
                                                'users.last_name',
                                                DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                                DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                            )
                                            ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                            ->leftJoin(
                                                DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                        FROM project_stage_information
                                                        WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                        GROUP BY account_id) as latest_stage'),
                                                'latest_stage.account_id', '=', 'accounts.id'
                                            )
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type);
                                            if ($sector_id != null) {
                                                $data2->where('clients.industry_id', $sector_id);
                                            }
                                            $data2 = $data2
                                            ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                            ->orderBy('accounts_count', 'desc')
                                            ->get();

                                                if($totalEvaluationCount > 0){
                                            $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $dataevaluation->where('clients.industry_id', $sector_id);
                                            }
                                            $dataevaluation = $dataevaluation
                                            ->get();}else{$dataevaluation = [];}

                                            if($totalClosedCount > 0){
                                            $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $dataclosed->where('clients.industry_id', $sector_id);
                                            }
                                            $dataclosed = $dataclosed
                                            ->get();}else{ $dataclosed = []; }

                                            if($totalApprovalCount > 0){

                                            $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $dataapproval->where('clients.industry_id', $sector_id);
                                            }
                                            $dataapproval = $dataapproval
                                            ->get();
                                            }else{ $dataapproval = []; }


                                            if($totalScoopingCount > 0){
                                            $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $datascoping->where('clients.industry_id', $sector_id);
                                            }
                                            $datascoping = $datascoping
                                            ->get();
                                            }else{ $datascoping = []; }

                                            if($totalProspectsCount > 0){
                                            $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $dataprospects->where('clients.industry_id', $sector_id);
                                            }
                                            $dataprospects = $dataprospects
                                            ->get();}else{ $dataprospects = []; }

                                            if($totalProjectsCount > 0){
                                            $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $dataprojects->where('clients.industry_id', $sector_id);
                                            }
                                            $dataprojects = $dataprojects
                                            ->get();}else{ $dataprojects = []; }

                                            if($totalLostCount > 0){
                                            $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $datalost->where('clients.industry_id', $sector_id);
                                            }
                                            $datalost = $datalost
                                            ->get();}else{ $datalost= []; }

                                            if($totalOverdueCount > 0){
                                            $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                            ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                            ->where('accounts.solution_type_id', $solution_type)
                                            ->where('accounts.solution_id', $solution)
                                            ->where('accounts.solution_sub_type_id', $solution_sub_type)
                                            ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                            )
                                            ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                                $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                    ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                            })
                                            ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                            ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                            ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                            ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                            ->join('users', 'users.id', '=', 'accounts.user_id')
                                            ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                            ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                            if ($sector_id != null) {
                                                $dataoverdue->where('clients.industry_id', $sector_id);
                                            }
                                            $dataoverdue = $dataoverdue
                                            ->get();}else{ $dataoverdue=[]; }





                                    return Inertia::render('Reports/allmanagers', [
                                        'accounts' => $stages,
                                        'totals' => $totals,
                                        'counts' => $data2,
                                        'closedaccountsdata' => $dataclosed,
                                        'evaluationaccountsdata' => $dataevaluation,
                                        'approvalaccountsdata' => $dataapproval,
                                        'scopingaccountsdata' => $datascoping,
                                        'prospectaccountsdata' => $dataprospects,
                                        'lostaccountsdata' => $datalost,
                                        'overdueaccountsdata' => $dataoverdue,
                                        'projectsaccountsdata' => $dataprojects,
                                        'startdate' => $startdate,
                                        'enddate' => $enddate
                                    ]);

                }
                if($solution_type != null && $solution != null && $solution_sub_type == null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('accounts.solution_type_id', $solution_type)
                    ->where('accounts.solution_id', $solution);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data
                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2
                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval
                                        ->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping
                                        ->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)
                                        ->where('accounts.solution_id', $solution)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);

                }
                if($solution_type != null && $solution == null && $solution_sub_type == null){

                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    )
                    ->where('accounts.solution_type_id', $solution_type);
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data

                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        )
                                        ->where('accounts.solution_type_id', $solution_type);
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2

                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval
                                        ->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping
                                        ->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])
                                        ->where('accounts.solution_type_id', $solution_type)

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }





                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);

                }
                if($solution_type == null && $solution == null && $solution_sub_type == null){
                    $data = User::select(
                        'users.id',
                        'users.first_name',
                        'users.last_name',
                        DB::raw('COUNT(accounts.id) as accounts_count'),

                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                        DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                    )
                    ->join('accounts', 'accounts.user_id', '=', 'users.id')
                    ->join('clients', 'clients.id', '=', 'accounts.client_id')
                    ->leftJoin(
                        DB::raw('(
                            SELECT
                                psi.account_id,
                                MAX(psi.project_stage_id) as project_stage_id,
                                jsonb_agg(psi.meta ORDER BY psi.updated_at) as meta,
                                jsonb_agg(c.contact_information ORDER BY psi.updated_at) as contact_information
                            FROM project_stage_information psi
                            LEFT JOIN accounts a ON a.id = psi.account_id
                            LEFT JOIN clients c ON c.id = a.client_id
                            WHERE psi.created_at >= \'' . $startdate . '\' AND psi.created_at <= \'' . $enddate . '\'
                            GROUP BY psi.account_id
                        ) as latest_stage'),
                        'latest_stage.account_id', '=', 'accounts.id'
                    );
                    if ($sector_id != null) {
                        $data->where('clients.industry_id', $sector_id);
                    }
                    $data = $data

                    ->groupBy('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('accounts_count', 'desc')
                    ->get();

                    // Initialize totals
                    $totalProspectsCount = 0;
                    $totalScoopingCount = 0;
                    $totalEvaluationCount = 0;
                    $totalApprovalCount = 0;
                    $totalClosedCount = 0;
                    $totalLostCount = 0;
                    $totalPresalesCount = 0;
                    $totalProjectsCount = 0;
                    $totalOverdueCount = 0;

                    // Calculate totals
                    foreach ($data as $user) {
                        $totalProspectsCount += $user->prospects_count;
                        $totalScoopingCount += $user->scooping_count;
                        $totalEvaluationCount += $user->evaluation_count;
                        $totalApprovalCount += $user->approval_count;
                        $totalClosedCount += $user->closed_count;
                        $totalLostCount += $user->lost_count;
                        $totalPresalesCount += $user->presales_count;
                        $totalProjectsCount += $user->projects_count;
                        $totalOverdueCount += $user->overdue_count;
                    }

                    $total = Account::count();

                    $totals = [
                        'total' => $total,
                        'prospects' => $totalProspectsCount,
                        'scooping' => $totalScoopingCount,
                        'evaluation' => $totalEvaluationCount,
                        'approval' => $totalApprovalCount,
                        'closed' => $totalClosedCount,
                        'lost' => $totalLostCount,
                        'presales' => $totalPresalesCount,
                        'projects' => $totalProjectsCount,
                        'overdue' => $totalOverdueCount,
                    ];


                                        $data2 = User::select(
                                            'users.id',
                                            'users.first_name',
                                            'users.last_name',
                                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as prospects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 2 THEN 1 ELSE 0 END) as scooping_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 3 THEN 1 ELSE 0 END) as evaluation_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 4 THEN 1 ELSE 0 END) as approval_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 7 THEN 1 ELSE 0 END) as presales_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 8 THEN 1 ELSE 0 END) as projects_count'),
                                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_count'),
                                        )
                                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id')
                                        ->leftJoin(
                                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                                    FROM project_stage_information
                                                    WHERE project_stage_information.created_at >= \'' . $startdate . '\' AND project_stage_information.created_at <= \'' . $enddate . '\'
                                                    GROUP BY account_id) as latest_stage'),
                                            'latest_stage.account_id', '=', 'accounts.id'
                                        );
                                        if ($sector_id != null) {
                                            $data2->where('clients.industry_id', $sector_id);
                                        }
                                        $data2 = $data2

                                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                                        ->orderBy('accounts_count', 'desc')
                                        ->get();

                                            if($totalEvaluationCount > 0){
                                        $dataevaluation = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataevaluation->where('clients.industry_id', $sector_id);
                                        }
                                        $dataevaluation = $dataevaluation
                                        ->get();}else{$dataevaluation = [];}

                                        if($totalClosedCount > 0){
                                        $dataclosed = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataclosed->where('clients.industry_id', $sector_id);
                                        }
                                        $dataclosed = $dataclosed
                                        ->get();}else{ $dataclosed = []; }

                                        if($totalApprovalCount > 0){

                                        $dataapproval = ProjectStageInformation::where('project_stage_information.project_stage_id', 4)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')

                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataapproval->where('clients.industry_id', $sector_id);
                                        }
                                        $dataapproval = $dataapproval
                                        ->get();
                                        }else{ $dataapproval = []; }


                                        if($totalScoopingCount > 0){
                                        $datascoping = ProjectStageInformation::where('project_stage_information.project_stage_id', 2)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datascoping->where('clients.industry_id', $sector_id);
                                        }
                                        $datascoping = $datascoping
                                        ->get();
                                        }else{ $datascoping = []; }

                                        if($totalProspectsCount > 0){
                                        $dataprospects = ProjectStageInformation::where('project_stage_information.project_stage_id', 1)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprospects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprospects = $dataprospects
                                        ->get();}else{ $dataprospects = []; }

                                        if($totalProjectsCount > 0){
                                        $dataprojects = ProjectStageInformation::where('project_stage_information.project_stage_id', 8)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataprojects->where('clients.industry_id', $sector_id);
                                        }
                                        $dataprojects = $dataprojects
                                        ->get();}else{ $dataprojects = []; }

                                        if($totalLostCount > 0){
                                        $datalost = ProjectStageInformation::where('project_stage_information.project_stage_id', 6)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $datalost->where('clients.industry_id', $sector_id);
                                        }
                                        $datalost = $datalost
                                        ->get();}else{ $datalost= []; }

                                        if($totalOverdueCount > 0){
                                        $dataoverdue = ProjectStageInformation::where('project_stage_information.project_stage_id', 9)
                                        ->whereBetween('project_stage_information.created_at', [$startdate, $enddate])

                                        ->select('accounts.* as accountsdata','project_stage_information.meta','project_stage_information.stage_information','project_stage_information.created_at as pdate','users.first_name as accountmanagerfirstname','users.last_name as accountmanagerlastname','users.email as accountmanageremail','users.phone as accountmanagerphone','client_types.name as clienttype','clients.name as clientname','clients.id as clientid','clients.email as clientemail','clients.phone as clientphone','clients.location as clientlocation','clients.website_url as clientwebsiteurl','clients.contact_information as mainclientcontactinformation','solution_types.solution_type_name','solutions.solution_name','solution_sub_types.solution_sub_type_name'
                                        )
                                        ->join(DB::raw('(SELECT account_id, MAX(created_at) as latest_created_at FROM project_stage_information GROUP BY account_id) as latest_stage'), function($join) {
                                            $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                ->on('project_stage_information.created_at', '=', 'latest_stage.latest_created_at');
                                        })
                                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')


                                        ->leftJoin('solution_types', 'solution_types.id', '=', 'accounts.solution_type_id')
                                        ->leftJoin('solutions', 'solutions.id', '=', 'accounts.solution_id')
                                        ->leftJoin('solution_sub_types', 'solution_sub_types.id', '=', 'accounts.solution_sub_type_id')
                                        ->join('users', 'users.id', '=', 'accounts.user_id')
                                        ->leftJoin('client_types', 'client_types.id', '=', 'accounts.client_type_id')
                                        ->join('clients', 'clients.id', '=', 'accounts.client_id');
                                        if ($sector_id != null) {
                                            $dataoverdue->where('clients.industry_id', $sector_id);
                                        }
                                        $dataoverdue = $dataoverdue
                                        ->get();}else{ $dataoverdue=[]; }

                                return Inertia::render('Reports/allmanagers', [
                                    'accounts' => $stages,
                                    'totals' => $totals,
                                    'counts' => $data2,
                                    'closedaccountsdata' => $dataclosed,
                                    'evaluationaccountsdata' => $dataevaluation,
                                    'approvalaccountsdata' => $dataapproval,
                                    'scopingaccountsdata' => $datascoping,
                                    'prospectaccountsdata' => $dataprospects,
                                    'lostaccountsdata' => $datalost,
                                    'overdueaccountsdata' => $dataoverdue,
                                    'projectsaccountsdata' => $dataprojects,
                                    'startdate' => $startdate,
                                    'enddate' => $enddate
                                ]);

                }
            }
        }

    }
}

