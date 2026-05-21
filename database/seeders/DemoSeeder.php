<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Truncate in safe order ───────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('revenues')->truncate();
        DB::table('project_stage_information')->truncate();
        DB::table('accounts')->truncate();
        DB::table('clients')->truncate();
        DB::table('users')->whereNot('email', 'superadmin@xrxcrm.com')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ─── Sales Team Users (role_id=4 Sales Person, dept_id=2 Sales) ──────
        $salesTeam = [
            ['first_name' => 'Brian',    'last_name' => 'Mwangi',   'email' => 'brian.mwangi@xtranet.co.ke',   'phone' => '0722100001', 'designation' => 'Sales Executive'],
            ['first_name' => 'Grace',    'last_name' => 'Wanjiku',  'email' => 'grace.wanjiku@xtranet.co.ke',  'phone' => '0722100002', 'designation' => 'Senior Sales Rep'],
            ['first_name' => 'Kevin',    'last_name' => 'Otieno',   'email' => 'kevin.otieno@xtranet.co.ke',   'phone' => '0722100003', 'designation' => 'Sales Executive'],
            ['first_name' => 'Sharon',   'last_name' => 'Chebet',   'email' => 'sharon.chebet@xtranet.co.ke',  'phone' => '0722100004', 'designation' => 'Sales Rep'],
            ['first_name' => 'James',    'last_name' => 'Kamau',    'email' => 'james.kamau@xtranet.co.ke',    'phone' => '0722100005', 'designation' => 'Sales Rep'],
            ['first_name' => 'Faith',    'last_name' => 'Njoroge',  'email' => 'faith.njoroge@xtranet.co.ke',  'phone' => '0722100006', 'designation' => 'Sales Executive'],
            ['first_name' => 'Michael',  'last_name' => 'Achieng',  'email' => 'michael.achieng@xtranet.co.ke','phone' => '0722100007', 'designation' => 'Sales Rep'],
            ['first_name' => 'Caroline', 'last_name' => 'Mutua',    'email' => 'caroline.mutua@xtranet.co.ke', 'phone' => '0722100008', 'designation' => 'Key Accounts Manager'],
        ];

        $userIds = [];
        foreach ($salesTeam as $u) {
            $userIds[] = DB::table('users')->insertGetId([
                'role_id'            => 4,
                'department_id'      => 2,
                'first_name'         => $u['first_name'],
                'last_name'          => $u['last_name'],
                'email'              => $u['email'],
                'phone'              => $u['phone'],
                'designation'        => $u['designation'],
                'calling_code'       => '254',
                'password'           => Hash::make('Demo@1234'),
                'email_verified_at'  => now(),
                'phone_verified_at'  => now(),
                'is_phone_verified'  => 1,
                'is_active'          => 1,
                'sales_rep'          => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        // Sales Manager (role_id=3)
        $managerId = DB::table('users')->insertGetId([
            'role_id'           => 3,
            'department_id'     => 2,
            'first_name'        => 'Samuel',
            'last_name'         => 'Kariuki',
            'email'             => 'samuel.kariuki@xtranet.co.ke',
            'phone'             => '0722100009',
            'designation'       => 'Sales Manager',
            'calling_code'      => '254',
            'password'          => Hash::make('Demo@1234'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'is_phone_verified' => 1,
            'is_active'         => 1,
            'sales_rep'         => 0,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // ─── Clients (Kenyan organizations) ──────────────────────────────────
        // industry_id: 1=Public Sector, 2=Saccos, 3=Banking, 4=Agriculture,
        //              5=Education, 6=NGOs, 7=Parastatals, 8=Building&Construction
        //              9=Insurance, 10=Health Care, 11=Micro Finance, 12=Manufacturing
        $clients = [
            [
                'name'     => 'Kenya Revenue Authority',
                'email'    => 'info@kra.go.ke',
                'phone'    => '0200004000',
                'location' => 'Times Tower, Nairobi',
                'website'  => 'https://www.kra.go.ke',
                'industry' => 1, // Public Sector
                'contacts' => [
                    ['name' => 'Peter Ndegwa',   'email' => 'peter.ndegwa@kra.go.ke',   'phone' => '0720111001', 'designation' => 'ICT Director'],
                    ['name' => 'Alice Waweru',   'email' => 'alice.waweru@kra.go.ke',   'phone' => '0720111002', 'designation' => 'Procurement Manager'],
                ],
            ],
            [
                'name'     => 'Safaricom PLC',
                'email'    => 'corporate@safaricom.co.ke',
                'phone'    => '0722000100',
                'location' => 'Safaricom House, Westlands, Nairobi',
                'website'  => 'https://www.safaricom.co.ke',
                'industry' => 12, // Manufacturing (Telco)
                'contacts' => [
                    ['name' => 'Josephine Mwenda', 'email' => 'j.mwenda@safaricom.co.ke', 'phone' => '0722000101', 'designation' => 'Enterprise Sales Lead'],
                ],
            ],
            [
                'name'     => 'Equity Bank Kenya',
                'email'    => 'equitybank@equitybank.co.ke',
                'phone'    => '0763000000',
                'location' => 'Equity Centre, Upper Hill, Nairobi',
                'website'  => 'https://www.equitybank.co.ke',
                'industry' => 3, // Banking
                'contacts' => [
                    ['name' => 'Thomas Kipchoge', 'email' => 't.kipchoge@equitybank.co.ke', 'phone' => '0763000001', 'designation' => 'Head of IT Infrastructure'],
                    ['name' => 'Agnes Moraa',     'email' => 'a.moraa@equitybank.co.ke',   'phone' => '0763000002', 'designation' => 'Procurement Officer'],
                ],
            ],
            [
                'name'     => 'Kenya National Highways Authority',
                'email'    => 'info@kenha.co.ke',
                'phone'    => '0202210000',
                'location' => 'Blue Shield Towers, Nairobi',
                'website'  => 'https://www.kenha.co.ke',
                'industry' => 7, // Parastatals
                'contacts' => [
                    ['name' => 'David Maina',   'email' => 'd.maina@kenha.co.ke',   'phone' => '0720222001', 'designation' => 'IT Manager'],
                ],
            ],
            [
                'name'     => 'Kenya Commercial Bank Group',
                'email'    => 'kcbgroup@kcbgroup.com',
                'phone'    => '0703070000',
                'location' => 'Kencom House, Nairobi',
                'website'  => 'https://www.kcbgroup.com',
                'industry' => 3, // Banking
                'contacts' => [
                    ['name' => 'Lilian Akinyi',  'email' => 'l.akinyi@kcbgroup.com',  'phone' => '0703070001', 'designation' => 'CIO Office'],
                    ['name' => 'Victor Ochieng', 'email' => 'v.ochieng@kcbgroup.com', 'phone' => '0703070002', 'designation' => 'Network Engineer'],
                ],
            ],
            [
                'name'     => 'Stima Sacco Society',
                'email'    => 'info@stimasacco.com',
                'phone'    => '0711038000',
                'location' => 'Anniversary Towers, Nairobi',
                'website'  => 'https://www.stimasacco.com',
                'industry' => 2, // Saccos
                'contacts' => [
                    ['name' => 'Ruth Njeru', 'email' => 'r.njeru@stimasacco.com', 'phone' => '0711038001', 'designation' => 'ICT Officer'],
                ],
            ],
            [
                'name'     => 'University of Nairobi',
                'email'    => 'vc@uonbi.ac.ke',
                'phone'    => '0202884000',
                'location' => 'University Way, Nairobi',
                'website'  => 'https://www.uonbi.ac.ke',
                'industry' => 5, // Education
                'contacts' => [
                    ['name' => 'Dr. Moses Mutuku', 'email' => 'm.mutuku@uonbi.ac.ke', 'phone' => '0722333001', 'designation' => 'Director ICT'],
                    ['name' => 'Jane Kioko',       'email' => 'j.kioko@uonbi.ac.ke',  'phone' => '0722333002', 'designation' => 'Procurement'],
                ],
            ],
            [
                'name'     => 'APA Insurance Kenya',
                'email'    => 'apainsurance@apollo.co.ke',
                'phone'    => '0703081000',
                'location' => 'Apollo Centre, Westlands, Nairobi',
                'website'  => 'https://www.apainsurance.org',
                'industry' => 9, // Insurance
                'contacts' => [
                    ['name' => 'Nancy Wambui', 'email' => 'n.wambui@apollo.co.ke', 'phone' => '0703081001', 'designation' => 'IT Manager'],
                ],
            ],
            [
                'name'     => 'Nairobi City County Government',
                'email'    => 'info@nairobi.go.ke',
                'phone'    => '0800720601',
                'location' => 'City Hall, Nairobi',
                'website'  => 'https://www.nairobi.go.ke',
                'industry' => 1, // Public Sector
                'contacts' => [
                    ['name' => 'John Kamande', 'email' => 'j.kamande@nairobi.go.ke', 'phone' => '0720444001', 'designation' => 'Director Digital Services'],
                ],
            ],
            [
                'name'     => 'Kenya Power & Lighting Company',
                'email'    => 'customercare@kplc.co.ke',
                'phone'    => '0703070707',
                'location' => 'Stima Plaza, Parklands, Nairobi',
                'website'  => 'https://www.kplc.co.ke',
                'industry' => 7, // Parastatals
                'contacts' => [
                    ['name' => 'Eric Mwangi',  'email' => 'e.mwangi@kplc.co.ke',  'phone' => '0703070708', 'designation' => 'Head of IT'],
                    ['name' => 'Winnie Odhiambo', 'email' => 'w.odhiambo@kplc.co.ke', 'phone' => '0703070709', 'designation' => 'Procurement Manager'],
                ],
            ],
            [
                'name'     => 'Gertrudes Children Hospital',
                'email'    => 'info@gertrudes.or.ke',
                'phone'    => '0203741388',
                'location' => 'Muthaiga, Nairobi',
                'website'  => 'https://www.gertrudes.or.ke',
                'industry' => 10, // Health Care
                'contacts' => [
                    ['name' => 'Dr. Anne Kimani', 'email' => 'a.kimani@gertrudes.or.ke', 'phone' => '0720555001', 'designation' => 'CEO'],
                ],
            ],
            [
                'name'     => 'Kenya Farmers Association Sacco',
                'email'    => 'info@kfa.co.ke',
                'phone'    => '0722607070',
                'location' => 'Westlands, Nairobi',
                'website'  => 'https://www.kfa.co.ke',
                'industry' => 2, // Saccos
                'contacts' => [
                    ['name' => 'Paul Njogu', 'email' => 'p.njogu@kfa.co.ke', 'phone' => '0722607071', 'designation' => 'ICT Manager'],
                ],
            ],
        ];

        $clientIds = [];
        foreach ($clients as $i => $c) {
            $contactJson = json_encode(array_map(fn($ct) => [
                'id'          => rand(100, 999),
                'name'        => $ct['name'],
                'email'       => $ct['email'],
                'phone'       => $ct['phone'],
                'designation' => $ct['designation'],
                'tag'         => 'Primary',
            ], $c['contacts']));

            $clientIds[] = DB::table('clients')->insertGetId([
                'name'                => $c['name'],
                'email'               => $c['email'],
                'phone'               => $c['phone'],
                'location'            => $c['location'],
                'website_url'         => $c['website'],
                'industry_id'         => $c['industry'],
                'user_id'             => $userIds[$i % count($userIds)],
                'contact_information' => $contactJson,
                'client_type_id'      => ($i % 3) + 1,
                'is_existing'         => $i < 5 ? 1 : 0,
                'comments'            => 'Demo client added for system preview.',
                'created_at'          => Carbon::now()->subDays(rand(30, 180)),
                'updated_at'          => now(),
            ]);
        }

        // ─── Accounts (deals) ────────────────────────────────────────────────
        // solution_type_id: 1=Internet, 2=IP Infra, 3=Security, 4=Cloud, 5=Software, 6=Print
        $accountDefs = [
            // [client_idx, user_idx, business_name, solution_type_id, stage_id, client_type_id]
            [0,  0, 'KRA Nairobi HQ Fibre Upgrade',         1, 5, 1], // Closed Deal
            [0,  0, 'KRA Times Tower Security CCTV',        3, 2, 1], // Scooping
            [1,  1, 'Safaricom Westlands WAN Link',         2, 5, 2], // Closed Deal
            [1,  1, 'Safaricom Cloud Migration Phase 1',    4, 3, 2], // Evaluation
            [2,  2, 'Equity Bank Upper Hill Internet',      1, 5, 1], // Closed Deal
            [2,  2, 'Equity Bank Branch CCTV Network',      3, 4, 3], // Approval
            [3,  3, 'KeNHA Headquarters Connectivity',      2, 1, 1], // Prospects
            [3,  3, 'KeNHA Road Monitoring System',         3, 2, 1], // Scooping
            [4,  4, 'KCB Group Core Banking Connectivity',  1, 5, 1], // Closed Deal
            [4,  4, 'KCB Cloud Backup Solution',            4, 3, 2], // Evaluation
            [5,  5, 'Stima Sacco HQ Internet Link',         1, 5, 2], // Closed Deal
            [5,  5, 'Stima Sacco Branch Connectivity',      2, 1, 1], // Prospects
            [6,  6, 'UoN Main Campus Fibre Ring',           1, 4, 1], // Approval
            [6,  6, 'UoN CCTV Surveillance System',        3, 3, 1], // Evaluation
            [7,  7, 'APA Insurance WAN Infrastructure',    2, 5, 2], // Closed Deal
            [7,  0, 'APA Insurance DR Site Setup',         4, 2, 2], // Scooping
            [8,  1, 'Nairobi County Digital Hub',           5, 1, 1], // Prospects
            [8,  2, 'City Hall CCTV & Access Control',      3, 2, 1], // Scooping
            [9,  3, 'Kenya Power Stima Plaza Internet',     1, 5, 1], // Closed Deal
            [9,  4, 'Kenya Power Smart Metering Infra',    2, 4, 1], // Approval
            [10, 5, 'Gertrudes Hospital EMR System',        5, 3, 1], // Evaluation
            [10, 6, 'Gertrudes Hospital Network Upgrade',   2, 1, 1], // Prospects
            [11, 7, 'KFA Sacco Core Banking Connectivity',  1, 5, 2], // Closed Deal
            [11, 0, 'KFA Branch Internet Links',            1, 2, 1], // Scooping
        ];

        $accountIds = [];
        foreach ($accountDefs as $def) {
            [$ci, $ui, $bizName, $solTypeId, $stageId, $ctId] = $def;
            $contactJson = json_encode([[
                'id'          => rand(200, 999),
                'name'        => $clients[$ci]['contacts'][0]['name'],
                'email'       => $clients[$ci]['contacts'][0]['email'],
                'phone'       => $clients[$ci]['contacts'][0]['phone'],
                'designation' => $clients[$ci]['contacts'][0]['designation'],
                'tag'         => 'Primary',
            ]]);

            $accountIds[] = DB::table('accounts')->insertGetId([
                'client_id'       => $clientIds[$ci],
                'client_type_id'  => $ctId,
                'user_id'         => $userIds[$ui],
                'solution_type_id'=> $solTypeId,
                'solution_id'     => 1,
                'business_name'   => $bizName,
                'contact_information' => $contactJson,
                'comments'        => 'Account created as part of demo dataset.',
                'stage'           => 1,
                'crm'             => 0,
                'created_at'      => Carbon::now()->subDays(rand(10, 150)),
                'updated_at'      => now(),
            ]);
        }

        // ─── Project Stage Information ────────────────────────────────────────
        // For each account, push it through logical stage history up to its current stage
        $stageProgression = [
            1 => [1],                // Prospects → just stage 1
            2 => [1, 2],             // Scooping
            3 => [1, 2, 3],          // Evaluation
            4 => [1, 2, 3, 4],       // Approval
            5 => [1, 2, 3, 4, 5],    // Closed Deal
        ];

        $stageNotes = [
            1 => ['Initial prospect identified via cold call.', 'Lead captured from referral.', 'Prospect from networking event.'],
            2 => ['Site survey completed.', 'Preliminary technical assessment done.', 'Scoping meeting held with client team.'],
            3 => ['RFP received and reviewed.', 'Technical proposal submitted.', 'Demo conducted at client premises.'],
            4 => ['Commercial proposal approved by board.', 'Awaiting LPO from procurement.', 'Legal review of contract ongoing.'],
            5 => ['Contract signed and LPO received.', 'Implementation completed.', 'Go-live confirmed. Client satisfied.'],
        ];

        foreach ($accountIds as $idx => $accId) {
            $currentStage = $accountDefs[$idx][4];
            $stages       = $stageProgression[$currentStage] ?? [1];
            $baseDate     = Carbon::now()->subDays(rand(30, 120));

            foreach ($stages as $offset => $stageId) {
                $note = $stageNotes[$stageId][array_rand($stageNotes[$stageId])];
                DB::table('project_stage_information')->insert([
                    'project_stage_id' => $stageId,
                    'account_id'       => $accId,
                    'stage_information'=> $note,
                    'meta'             => json_encode(['updated_by' => 'demo']),
                    'created_at'       => $baseDate->copy()->addDays($offset * 7),
                    'updated_at'       => $baseDate->copy()->addDays($offset * 7),
                ]);
            }
        }

        // ─── Revenues (only for Closed Deal accounts — stage 5) ──────────────
        $closedAccountIndices = array_keys(array_filter(
            $accountDefs,
            fn($d) => $d[4] === 5
        ));

        $revenueData = [
            480000, 1250000, 975000, 320000, 2100000,
            560000, 890000,  1450000, 234000, 1780000,
        ];

        foreach ($closedAccountIndices as $i => $accIdx) {
            $amount = $revenueData[$i % count($revenueData)];
            $daysAgo = rand(5, 60);
            DB::table('revenues')->insert([
                'account_id' => $accountIds[$accIdx],
                'amount'     => $amount,
                'date'       => Carbon::now()->subDays($daysAgo)->toDateString(),
                'created_at' => Carbon::now()->subDays($daysAgo),
                'updated_at' => Carbon::now()->subDays($daysAgo),
            ]);
        }

        $this->command->info('✅  Demo data seeded successfully.');
        $this->command->info('    Users: ' . count($userIds) . ' sales reps + 1 manager');
        $this->command->info('    Clients: ' . count($clientIds));
        $this->command->info('    Accounts: ' . count($accountIds));
        $this->command->info('    Revenues: ' . count($closedAccountIndices) . ' closed deals');
        $this->command->info('    Password for all demo users: Demo@1234');
    }
}
