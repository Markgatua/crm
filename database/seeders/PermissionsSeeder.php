<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ── Roles ─────────────────────────────────────────────────────────────
        $roles = [
            ['id' => 1, 'name' => 'Super Admin', 'description' => 'Has full access to all modules and permissions.', 'is_active' => 1],
            ['id' => 2, 'name' => 'Admin',       'description' => 'Has access to most modules except user/role/permission management.', 'is_active' => 1],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                array_merge($role, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()])
            );
        }

        // ── Super Admin user ───────────────────────────────────────────────────
        $superAdmin = [
            'first_name'   => 'Super',
            'last_name'    => 'Admin',
            'email'        => 'superadmin@xrx.com',
            'phone'        => '0700000000',
            'calling_code' => '+254',
            'role_id'      => 1,
            'department_id'=> 1,
            'sales_rep'    => 0,
            'is_active'    => 1,
            'password'     => Hash::make('SuperAdmin@2024!'),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ];

        DB::table('users')->updateOrInsert(
            ['email' => $superAdmin['email']],
            $superAdmin
        );

        // ── Permissions ────────────────────────────────────────────────────────
        $permissions = [
            ["module" => "Permissions", "actions" => ["assign"]],
            ["module" => "Dashboard", "actions" => ["view"]],
            ["module" => "SystemLogs", "actions" => ["view"]],
            ["module" => "Roles", "actions" => ["create", "view", "edit"]],
            ["module" => "Users", "actions" => ["create", "view", "delete", "edit"]],
            ["module" => "Departments", "actions" => ["create", "view", "delete", "edit"]],
            ["module" => "Accounts", "actions" => ["create", "view", "delete", "edit"]],
            ["module" => "Reports", "actions" => ["create", "view", "delete", "edit"]],
        ];

        foreach ($permissions as $value) {
            $module = $value["module"];
            $actions = $value["actions"];

            foreach ($actions as $action) {
                if (DB::table("permissions")->where("module", $module)->where("name", $action)->count() == 0) {
                    DB::table('permissions')->insert([
                        "name" => $action,
                        "guard_name" => "web" . $module . $action,
                        "read_name" => $action.' '.$module,
                        "module" => $module,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now()
                    ]);
                }
            }
        }

        //set admin role to have all permissions
        //first delete the previous permissions
        DB::table("role_has_permissions")->where("role_id", 1)->delete();
        DB::table("role_has_permissions")->where("role_id", 2)->delete();
        //set the new permissions

        $permissions = DB::table("permissions")->get();
        foreach ($permissions as $value) {
            DB::table("role_has_permissions")->insert([
                "permission_id" => $value->id,
                "role_id" => 1,
                "is_active" => 1
            ]);
        }

        $admin_permissions = DB::table("permissions")->where('module','!=','Permissions')->where('module','!=','Users')->where('module','!=','Roles')->get();
        foreach ($admin_permissions as $value) {
            DB::table("role_has_permissions")->insert([
                "permission_id" => $value->id,
                "role_id" => 2,
                "is_active" => 1
            ]);
        }


    }
}
