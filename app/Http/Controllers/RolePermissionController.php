<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function store(Role $role, Permission $permission)
    {
        $role->givePermissionTo($permission);
    }

    public function destroy(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission);
    }
}