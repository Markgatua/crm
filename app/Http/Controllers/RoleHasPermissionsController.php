<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permissions;
use App\Models\RoleHasPermissions;
use Inertia\Inertia;


class RoleHasPermissionsController extends Controller
{
    public function index(){
        $roles = Role::all();
        $permissions = Permissions::all();
        $allmodules = Permissions::groupBy('module')->get('module');

        return Inertia::render('Settings/RolePermissions/index',[
            'roles' => $roles,
            'permissions' => $permissions,
            'modules' => $allmodules
        ]);
    }
}
