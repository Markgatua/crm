<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
use App\Models\Client;
use App\Models\Revenue;
use Inertia\Inertia;
use App\Models\ProjectStageInformation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $role_id = $user->role_id;
        $user_id = $user->id;

        if($role_id == 4){
            $accounts = Account::where('user_id',$user_id)->count();
            $users = User::where('sales_rep',1)->count();
            $clients = Client::where('user_id',$user_id)->count();
            $revenue = Revenue::sum('amount');

            $latestProjectStageInfo = DB::table('project_stage_information')
                            ->select('account_id', DB::raw('MAX(id) as latest_id'))
                            ->groupBy('account_id');


            $newaccounts = Account::where('accounts.user_id',$user_id)->select(DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 5 THEN accounts.id ELSE NULL END) as newaccountscount'))
                        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
                            $join->on('accounts.id', '=', 'latest_psi.account_id');
                        })->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')->get();



            $popularprojects = ProjectStageInformation::where('accounts.user_id',$user_id)->select('project_stage_information.*', 'accounts.business_name','project_stages.name as stage','clients.name as clientname')
                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                        ->join('clients','clients.id','=','accounts.client_id')
                        ->join('project_stages', 'project_stages.id','=','project_stage_information.project_stage_id')
                        ->orderBy('project_stage_information.updated_at', 'desc')
                        ->limit(3)
                        ->get();

            $topUsers = User::select(
                            'users.id',
                            'users.first_name',
                            'users.last_name',
                            DB::raw('COUNT(accounts.id) as accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 1 THEN 1 ELSE 0 END) as new_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id IN ( 2, 3, 4, 7) THEN 1 ELSE 0 END) as open_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_accounts_count'),
                        )
                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                        ->leftJoin('project_stage_information', 'project_stage_information.account_id', '=', 'accounts.id')
                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                        ->orderBy('accounts_count', 'desc')
                        ->limit(3)
                        ->get();
            $recentSales = Revenue::select(
                                'revenues.id',
                                'revenues.amount',
                                'revenues.date',
                                'accounts.business_name',
                                'revenues.account_id',
                                'users.first_name',
                                'users.last_name'
                            )
                            ->join('accounts', 'accounts.id', '=', 'revenues.account_id')
                            ->join('users', 'users.id', '=', 'accounts.user_id')
                            ->orderBy('revenues.date', 'desc')
                            ->limit(3)
                            ->get();

                    $topSales = Revenue::select(
                        'revenues.id',
                        'revenues.amount',
                        'revenues.date',
                        'accounts.business_name',
                        'users.first_name',
                        'clients.name as clientname',
                        'users.last_name',
                        'users.email'
                    )
                    ->join('accounts', 'accounts.id', '=', 'revenues.account_id')
                    ->join('users', 'users.id', '=', 'accounts.user_id')
                    ->join('clients','clients.id','=','accounts.client_id')
                    ->orderBy('revenues.amount', 'desc')
                    ->limit(3)
                    ->get();


                    // dd($recentSales);



                    $months = collect();
                    $currentDate = Carbon::now()->startOfMonth();

                    for ($i = 0; $i < 12; $i++) {
                        $months->push($currentDate->copy()->subMonths($i)->format('Y-m'));
                    }
                    $months = $months->reverse()->values();

                    // Subquery to get the latest project_stage_information record for each account


                    // Perform the query to get the counts
                    $accountCounts = Account::where('accounts.user_id',$user_id)->select(
                            DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\') as month'),
                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 5 THEN accounts.id ELSE NULL END) as closed_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 1 THEN accounts.id ELSE NULL END) as new_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id IN (2, 3, 4, 7) THEN accounts.id ELSE NULL END) as open_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 6 THEN accounts.id ELSE NULL END) as lost_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 9 THEN accounts.id ELSE NULL END) as overdue_accounts_count')
                        )
                        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
                            $join->on('accounts.id', '=', 'latest_psi.account_id');
                        })
                        ->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
                        ->where('accounts.created_at', '>=', $currentDate->copy()->subMonths(12))
                        ->groupBy(DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\')'))
                        ->orderBy(DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\')'), 'asc')
                        ->get();

                                                // Convert the result to a collection and key by month for easier manipulation
                                                $accountCounts = $accountCounts->keyBy('month');

                                                // Create a new collection with all months, ensuring that each month is included
                                                $accountsGraph = $months->map(function($month) use ($accountCounts) {
                                                    // Convert month to DateTime object for formatting
                                                    $date = Carbon::createFromFormat('Y-m', $month);
                                                    return [
                                                        'month' => $date->format('M'), // Format month as short text (e.g., Jan, Feb, Mar)
                                                        'accounts_count' => $accountCounts->get($month)->accounts_count ?? 0,
                                                        'closed_accounts_count' => $accountCounts->get($month)->closed_accounts_count ?? 0,
                                                        'open_accounts_count' => $accountCounts->get($month)->open_accounts_count ?? 0,
                                                        'lost_accounts_count' => $accountCounts->get($month)->lost_accounts_count ?? 0,
                                                        'overdue_accounts_count' => $accountCounts->get($month)->overdue_accounts_count ?? 0,
                                                        'new_accounts_count' => $accountCounts->get($month)->new_accounts_count ?? 0,
                                                    ];
                                                });



                                                $months = collect();
                            $currentDate = Carbon::now()->startOfMonth();

                            for ($i = 0; $i < 12; $i++) {
                                $months->push($currentDate->copy()->subMonths($i)->format('Y-m'));
                            }
                            $months = $months->reverse()->values();

                            // Perform the query to get the revenue counts
                            $revenueCounts = Revenue::select(
                                    DB::raw('DATE_FORMAT(date, \'%Y-%m\') as month'),
                                    DB::raw('SUM(amount) as total_revenue')
                                )
                                ->where('date', '>=', $currentDate->copy()->subMonths(12))
                                ->groupBy(DB::raw('DATE_FORMAT(date, \'%Y-%m\')'))
                                ->orderBy(DB::raw('DATE_FORMAT(date, \'%Y-%m\')'), 'asc')
                                ->get();

                            // Convert the result to a collection and key by month for easier manipulation
                            $revenueCounts = $revenueCounts->keyBy('month');

                            // Create a new collection with all months, ensuring that each month is included
                            $revenuesGraph = $months->map(function($month) use ($revenueCounts) {
                                // Convert month to DateTime object for formatting
                                $date = Carbon::createFromFormat('Y-m', $month);
                                return [
                                    'month' => $date->format('M'), // Format month as short text (e.g., Jan, Feb, Mar)
                                    'total_revenue' => $revenueCounts->get($month)->total_revenue ?? 0,
                                ];
                            });

                            $datar = User::where('users.id',Auth::user()->id)->select(
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
                                DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                        FROM project_stage_information
                                        GROUP BY account_id) as latest_stage'),
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


                            foreach ($datar as $user) {
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

                            $total = Account::where('user_id',Auth::user()->id)->count();

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

                            // dd($revenuesGraph);


                            $datah = DB::table('project_stage_information')
                            ->join('accounts', 'project_stage_information.account_id', '=', 'accounts.id')
                            ->join(
                                DB::raw('(SELECT account_id, MAX(project_stage_id) as latest_stage_id
                                        FROM project_stage_information
                                        GROUP BY account_id) as latest_stage'),
                                function($join) {
                                    $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                        ->on('project_stage_information.project_stage_id', '=', 'latest_stage.latest_stage_id');
                                }
                            )
                            ->where('accounts.user_id', Auth::user()->id)
                            ->where('project_stage_information.project_stage_id', 5)
                            ->select('project_stage_information.*')
                            ->get();

                                    $myArray = [];
                                    foreach($datah as $data) {
                                        foreach(json_decode($data->meta) as $datameta) {
                                            if (isset($datameta->key) && $datameta->key === 'Deal Amount') {
                                                array_push($myArray, (float) str_replace(",","", $datameta->value));
                                            }
                                        }
                                    }

                                    $datae = DB::table('project_stage_information')
                                            ->join('accounts', 'project_stage_information.account_id', '=', 'accounts.id')
                                            ->join(
                                                DB::raw('(SELECT account_id, MAX(project_stage_id) as latest_stage_id
                                                        FROM project_stage_information
                                                        GROUP BY account_id) as latest_stage'),
                                                function($join) {
                                                    $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                                        ->on('project_stage_information.project_stage_id', '=', 'latest_stage.latest_stage_id');
                                                }
                                            )
                                            ->where('accounts.user_id', Auth::user()->id)
                                            ->where('project_stage_information.project_stage_id', 3)
                                            ->select('project_stage_information.*')
                                            ->get();

                                    $myArraye = [];
                                    foreach($datae as $data) {
                                        foreach(json_decode($data->meta) as $datameta) {
                                            if (isset($datameta->key) && $datameta->key === 'Expected Sale Value') {
                                                array_push($myArraye, $datameta->value);
                                            }
                                        }
                                    }


                                 $myArraye = array_sum($myArraye);
                                 $myArray = array_sum($myArray);


                                    return Inertia::render('Dashboard',[
                                        'accounts' => $accounts,
                                        'personnels' => $users,
                                        'newaccounts' => $newaccounts,
                                        'clients' => $clients,
                                        'topsales' => $topSales,
                                        'recentsales' => $recentSales,
                                        'topusers' => $topUsers,
                                        'popularprojects' => $popularprojects,
                                        'accountsgraph' => $accountsGraph,
                                        'revenuesgraph' => $revenuesGraph,
                                        'totals' => $totals,
                                        'totalrevenueforclosedaccounts' => $myArray,
                                        'totalrevenueforevaluationaccounts' => $myArraye,
                                    ]);


        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////

        }

        if($role_id == 5){
            $accounts = Account::where('crm',1)->count();
            $users = User::where('sales_rep',1)->count();
            $clients = Client::where('is_existing',1)->count();
            $revenue = Revenue::sum('amount');

            $latestProjectStageInfo = DB::table('project_stage_information')
                            ->select('account_id', DB::raw('MAX(id) as latest_id'))
                            ->groupBy('account_id');


            $newaccounts = Account::where('accounts.crm',1)->select(DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 5 THEN accounts.id ELSE NULL END) as newaccountscount'))
                        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
                            $join->on('accounts.id', '=', 'latest_psi.account_id');
                        })->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')->get();



            $popularprojects = ProjectStageInformation::where('accounts.crm',1)->select('project_stage_information.*', 'accounts.business_name','project_stages.name as stage','clients.name as clientname')
                        ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                        ->join('clients','clients.id','=','accounts.client_id')
                        ->join('project_stages', 'project_stages.id','=','project_stage_information.project_stage_id')
                        ->orderBy('project_stage_information.updated_at', 'desc')
                        ->limit(3)
                        ->get();

            $topUsers = User::select(
                            'users.id',
                            'users.first_name',
                            'users.last_name',
                            DB::raw('COUNT(accounts.id) as accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 1 THEN 1 ELSE 0 END) as new_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id IN ( 2, 3, 4, 7) THEN 1 ELSE 0 END) as open_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_accounts_count'),
                            DB::raw('SUM(CASE WHEN project_stage_information.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_accounts_count'),
                        )
                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                        ->leftJoin('project_stage_information', 'project_stage_information.account_id', '=', 'accounts.id')
                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                        ->orderBy('accounts_count', 'desc')
                        ->limit(3)
                        ->get();
            $recentSales = Revenue::select(
                                'revenues.id',
                                'revenues.amount',
                                'revenues.date',
                                'accounts.business_name',
                                'revenues.account_id',
                                'users.first_name',
                                'users.last_name'
                            )
                            ->join('accounts', 'accounts.id', '=', 'revenues.account_id')
                            ->join('users', 'users.id', '=', 'accounts.user_id')
                            ->orderBy('revenues.date', 'desc')
                            ->limit(3)
                            ->get();

                    $topSales = Revenue::select(
                        'revenues.id',
                        'revenues.amount',
                        'revenues.date',
                        'accounts.business_name',
                        'users.first_name',
                        'clients.name as clientname',
                        'users.last_name',
                        'users.email'
                    )
                    ->join('accounts', 'accounts.id', '=', 'revenues.account_id')
                    ->join('users', 'users.id', '=', 'accounts.user_id')
                    ->join('clients','clients.id','=','accounts.client_id')
                    ->orderBy('revenues.amount', 'desc')
                    ->limit(3)
                    ->get();


                    // dd($recentSales);



                    $months = collect();
                    $currentDate = Carbon::now()->startOfMonth();

                    for ($i = 0; $i < 12; $i++) {
                        $months->push($currentDate->copy()->subMonths($i)->format('Y-m'));
                    }
                    $months = $months->reverse()->values();

                    // Subquery to get the latest project_stage_information record for each account


                    // Perform the query to get the counts
                    $accountCounts = Account::where('accounts.crm',1)->select(
                            DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\') as month'),
                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 5 THEN accounts.id ELSE NULL END) as closed_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 1 THEN accounts.id ELSE NULL END) as new_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id IN (2, 3, 4, 7) THEN accounts.id ELSE NULL END) as open_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 6 THEN accounts.id ELSE NULL END) as lost_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 9 THEN accounts.id ELSE NULL END) as overdue_accounts_count')
                        )
                        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
                            $join->on('accounts.id', '=', 'latest_psi.account_id');
                        })
                        ->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
                        ->where('accounts.created_at', '>=', $currentDate->copy()->subMonths(12))
                        ->groupBy(DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\')'))
                        ->orderBy(DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\')'), 'asc')
                        ->get();

                                                // Convert the result to a collection and key by month for easier manipulation
                                                $accountCounts = $accountCounts->keyBy('month');

                                                // Create a new collection with all months, ensuring that each month is included
                                                $accountsGraph = $months->map(function($month) use ($accountCounts) {
                                                    // Convert month to DateTime object for formatting
                                                    $date = Carbon::createFromFormat('Y-m', $month);
                                                    return [
                                                        'month' => $date->format('M'), // Format month as short text (e.g., Jan, Feb, Mar)
                                                        'accounts_count' => $accountCounts->get($month)->accounts_count ?? 0,
                                                        'closed_accounts_count' => $accountCounts->get($month)->closed_accounts_count ?? 0,
                                                        'open_accounts_count' => $accountCounts->get($month)->open_accounts_count ?? 0,
                                                        'lost_accounts_count' => $accountCounts->get($month)->lost_accounts_count ?? 0,
                                                        'overdue_accounts_count' => $accountCounts->get($month)->overdue_accounts_count ?? 0,
                                                        'new_accounts_count' => $accountCounts->get($month)->new_accounts_count ?? 0,
                                                    ];
                                                });



                                                $months = collect();
                            $currentDate = Carbon::now()->startOfMonth();

                            for ($i = 0; $i < 12; $i++) {
                                $months->push($currentDate->copy()->subMonths($i)->format('Y-m'));
                            }
                            $months = $months->reverse()->values();

                            // Perform the query to get the revenue counts
                            $revenueCounts = Revenue::select(
                                    DB::raw('DATE_FORMAT(date, \'%Y-%m\') as month'),
                                    DB::raw('SUM(amount) as total_revenue')
                                )
                                ->where('date', '>=', $currentDate->copy()->subMonths(12))
                                ->groupBy(DB::raw('DATE_FORMAT(date, \'%Y-%m\')'))
                                ->orderBy(DB::raw('DATE_FORMAT(date, \'%Y-%m\')'), 'asc')
                                ->get();

                            // Convert the result to a collection and key by month for easier manipulation
                            $revenueCounts = $revenueCounts->keyBy('month');

                            // Create a new collection with all months, ensuring that each month is included
                            $revenuesGraph = $months->map(function($month) use ($revenueCounts) {
                                // Convert month to DateTime object for formatting
                                $date = Carbon::createFromFormat('Y-m', $month);
                                return [
                                    'month' => $date->format('M'), // Format month as short text (e.g., Jan, Feb, Mar)
                                    'total_revenue' => $revenueCounts->get($month)->total_revenue ?? 0,
                                ];
                            });

                                                // dd($revenuesGraph);


                                        return Inertia::render('Dashboard',[
                                        'accounts' => $accounts,
                                        'personnels' => $users,
                                        'newaccounts' => $newaccounts,
                                        'clients' => $clients,
                                        'topsales' => $topSales,
                                        'recentsales' => $recentSales,
                                        'topusers' => $topUsers,
                                        'popularprojects' => $popularprojects,
                                        'accountsgraph' => $accountsGraph,
                                        'revenuesgraph' => $revenuesGraph,
                                    ]);


        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////

        }










        $accounts = Account::count();
        $users = User::where('sales_rep',1)->count();
        $clients = Client::count();
        $revenue = Revenue::sum('amount');

        $latestProjectStageInfo = DB::table('project_stage_information')
        ->where('project_stage_id','!=',10)

                        ->select('account_id', DB::raw('MAX(id) as latest_id'))
                        ->groupBy('account_id');

                        // return json_encode($latestProjectStageInfo);


        $newaccounts = Account::select(DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 1 THEN accounts.id ELSE NULL END) as newaccountscount'))
                        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
                            $join->on('accounts.id', '=', 'latest_psi.account_id');
                        })->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
                       ->get();

        $scopingaccounts = Account::select(DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 2 THEN accounts.id ELSE NULL END) as newaccountscount'))
        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
            $join->on('accounts.id', '=', 'latest_psi.account_id');
        })->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
       ->get();

        $closedaccounts = Account::select(DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 5 THEN accounts.id ELSE NULL END) as newaccountscount'))
        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
            $join->on('accounts.id', '=', 'latest_psi.account_id');
        })->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
       ->get();

       $lostaccounts = Account::select(DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 6 THEN accounts.id ELSE NULL END) as newaccountscount'))
        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
            $join->on('accounts.id', '=', 'latest_psi.account_id');
        })->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
       ->get();

        // $newaccounts = Account::select(DB::raw('COUNT(DIST)
        //     ->leftJoin('project_stage_information','project_stage_information.id','=','')

        //             //    return json_encode($newaccounts);

        $popularprojects = ProjectStageInformation::select('project_stage_information.*', 'accounts.business_name','project_stages.name as stage','clients.name as clientname')
                    ->join('accounts', 'accounts.id', '=', 'project_stage_information.account_id')
                    ->join('clients','clients.id','=','accounts.client_id')
                    ->join('project_stages', 'project_stages.id','=','project_stage_information.project_stage_id')
                    ->orderBy('project_stage_information.updated_at', 'desc')
                    ->limit(3)
                    ->get();

                    $topUsers = User::select(
                            'users.id',
                            'users.first_name',
                            'users.last_name',
                            DB::raw('COUNT(accounts.id) as accounts_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 1 THEN 1 ELSE 0 END) as new_accounts_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 5 THEN 1 ELSE 0 END) as closed_accounts_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id IN (2, 3, 4, 7) THEN 1 ELSE 0 END) as open_accounts_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 6 THEN 1 ELSE 0 END) as lost_accounts_count'),
                            DB::raw('SUM(CASE WHEN latest_stage.project_stage_id = 9 THEN 1 ELSE 0 END) as overdue_accounts_count')
                        )
                        ->join('accounts', 'accounts.user_id', '=', 'users.id')
                        ->leftJoin(
                            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                    FROM project_stage_information
                                    GROUP BY account_id) as latest_stage'),
                            'latest_stage.account_id', '=', 'accounts.id'
                        )
                        ->groupBy('users.id', 'users.first_name', 'users.last_name')
                        ->orderBy('accounts_count', 'desc')
                        // ->limit(3)
                        ->get();

       $recentSales = Revenue::select(
                        'revenues.id',
                        'revenues.amount',
                        'revenues.date',
                        'accounts.business_name',
                        'revenues.account_id',
                        'users.first_name',
                        'users.last_name'
                    )
                    ->join('accounts', 'accounts.id', '=', 'revenues.account_id')
                    ->join('users', 'users.id', '=', 'accounts.user_id')
                    ->orderBy('revenues.date', 'desc')
                    ->limit(3)
                    ->get();

                    $topSales = Revenue::select(
                        'revenues.id',
                        'revenues.amount',
                        'revenues.date',
                        'accounts.business_name',
                        'users.first_name',
                        'clients.name as clientname',
                        'users.last_name',
                        'users.email'
                    )
                    ->join('accounts', 'accounts.id', '=', 'revenues.account_id')
                    ->join('users', 'users.id', '=', 'accounts.user_id')
                    ->join('clients','clients.id','=','accounts.client_id')
                    ->orderBy('revenues.amount', 'desc')
                    ->limit(3)
                    ->get();


                    // dd($recentSales);



                    $months = collect();
                    $currentDate = Carbon::now()->startOfMonth();

                    for ($i = 0; $i < 12; $i++) {
                        $months->push($currentDate->copy()->subMonths($i)->format('Y-m'));
                    }
                    $months = $months->reverse()->values();

                    // Subquery to get the latest project_stage_information record for each account


                    // Perform the query to get the counts
                    $accountCounts = Account::select(
                            DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\') as month'),
                            DB::raw('COUNT(DISTINCT accounts.id) as accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 5 THEN accounts.id ELSE NULL END) as closed_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 1 THEN accounts.id ELSE NULL END) as new_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id IN (2, 3, 4, 7) THEN accounts.id ELSE NULL END) as open_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 6 THEN accounts.id ELSE NULL END) as lost_accounts_count'),
                            DB::raw('COUNT(DISTINCT CASE WHEN psi.project_stage_id = 9 THEN accounts.id ELSE NULL END) as overdue_accounts_count')
                        )
                        ->leftJoinSub($latestProjectStageInfo, 'latest_psi', function($join) {
                            $join->on('accounts.id', '=', 'latest_psi.account_id');
                        })
                        ->leftJoin('project_stage_information as psi', 'latest_psi.latest_id', '=', 'psi.id')
                        ->where('accounts.created_at', '>=', $currentDate->copy()->subMonths(12))
                        ->groupBy(DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\')'))
                        ->orderBy(DB::raw('DATE_FORMAT(accounts.created_at, \'%Y-%m\')'), 'asc')
                        ->get();

                    // Convert the result to a collection and key by month for easier manipulation
                    $accountCounts = $accountCounts->keyBy('month');

                    // Create a new collection with all months, ensuring that each month is included
                    $accountsGraph = $months->map(function($month) use ($accountCounts) {
                        // Convert month to DateTime object for formatting
                        $date = Carbon::createFromFormat('Y-m', $month);
                        return [
                            'month' => $date->format('M'), // Format month as short text (e.g., Jan, Feb, Mar)
                            'accounts_count' => $accountCounts->get($month)->accounts_count ?? 0,
                            'closed_accounts_count' => $accountCounts->get($month)->closed_accounts_count ?? 0,
                            'open_accounts_count' => $accountCounts->get($month)->open_accounts_count ?? 0,
                            'lost_accounts_count' => $accountCounts->get($month)->lost_accounts_count ?? 0,
                            'overdue_accounts_count' => $accountCounts->get($month)->overdue_accounts_count ?? 0,
                            'new_accounts_count' => $accountCounts->get($month)->new_accounts_count ?? 0,
                        ];
                    });



                    $months = collect();
$currentDate = Carbon::now()->startOfMonth();

for ($i = 0; $i < 12; $i++) {
    $months->push($currentDate->copy()->subMonths($i)->format('Y-m'));
}
$months = $months->reverse()->values();

// Perform the query to get the revenue counts
$revenueCounts = Revenue::select(
        DB::raw('DATE_FORMAT(date, \'%Y-%m\') as month'),
        DB::raw('SUM(amount) as total_revenue')
    )
    ->where('date', '>=', $currentDate->copy()->subMonths(12))
    ->groupBy(DB::raw('DATE_FORMAT(date, \'%Y-%m\')'))
    ->orderBy(DB::raw('DATE_FORMAT(date, \'%Y-%m\')'), 'asc')
    ->get();

// Convert the result to a collection and key by month for easier manipulation
$revenueCounts = $revenueCounts->keyBy('month');

// Create a new collection with all months, ensuring that each month is included
$revenuesGraph = $months->map(function($month) use ($revenueCounts) {
    // Convert month to DateTime object for formatting
    $date = Carbon::createFromFormat('Y-m', $month);
    return [
        'month' => $date->format('M'), // Format month as short text (e.g., Jan, Feb, Mar)
        'total_revenue' => $revenueCounts->get($month)->total_revenue ?? 0,
    ];
});


        $datar = User::select(
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
            DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                    FROM project_stage_information
                    GROUP BY account_id) as latest_stage'),
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


        foreach ($datar as $user) {
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

        // dd($revenuesGraph);


            $datah = ProjectStageInformation::where('project_stage_information.project_stage_id', 5)
                ->join(DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                FROM project_stage_information
                                GROUP BY account_id) as latest_stage'),
                    function($join) {
                        $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                ->on('project_stage_information.project_stage_id', '=', 'latest_stage.project_stage_id');
                    })
                ->get();

                $myArray = [];
                foreach($datah as $data) {
                    foreach($data->meta as $datameta) {
                        if (isset($datameta->key) && $datameta->key === 'Deal Amount') {
                            array_push($myArray, (float) str_replace(",","", $datameta->value));
                        }
                    }
                }

                $datae = ProjectStageInformation::where('project_stage_information.project_stage_id', 3)
                ->join(DB::raw('(SELECT account_id, MAX(project_stage_id) as project_stage_id
                                FROM project_stage_information
                                GROUP BY account_id) as latest_stage'),
                    function($join) {
                        $join->on('project_stage_information.account_id', '=', 'latest_stage.account_id')
                                ->on('project_stage_information.project_stage_id', '=', 'latest_stage.project_stage_id');
                    })
                ->get();

                $myArraye = [];
                foreach($datae as $data) {
                    foreach(($data->meta) as $datameta) {
                        if (isset($datameta->key) && $datameta->key === 'Expected Sale Value') {
                            array_push($myArraye, $datameta->value);
                        }
                    }
                }

                // foreach ($myArraye as $key => $value) {
                //     if (!is_numeric($value)) {
                //         throw new \Exception("Non-numeric value found at index $key: $value");
                //     }
                // }
             $myArraye = $myArraye;
             $myArray = $myArray;




        return Inertia::render('Dashboard',[
            'accounts' => $accounts,
            'personnels' => $users,
            'newaccounts' => $newaccounts,
            'clients' => $clients,
            'topsales' => $topSales,
            'recentsales' => $recentSales,
            'topusers' => $topUsers,
            'popularprojects' => $popularprojects,
            'accountsgraph' => $accountsGraph,
            'revenuesgraph' => $revenuesGraph,
            'totals' => $totals,
            'totalrevenueforclosedaccounts' => $myArray,
            'totalrevenueforevaluationaccounts' => $myArraye,
        ]);
    }

}
