<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        $users= User::with(['roles.permissions', 'permissions'])->get();

        return response()->json(['users' => $users]);
    }

    public function store(User $user, Role $role)
    {
        $user->assignRole($role);
    }

    public function destroy(User $user, Role $role)
    {
        $user->removeRole($role);
    }
}