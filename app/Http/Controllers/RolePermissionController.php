<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json(['roles' => $roles]);
    }

    public function store(Role $role, Permission $permission)
    {
        $role->givePermissionTo($permission);
    }

    public function destroy(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission);
    }
}