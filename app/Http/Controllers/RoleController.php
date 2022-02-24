<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            'roles' => $roles
        ]);
    }

    public function store()
    {
        $attribute = request()->validate([
            'name' => ['required', Rule::unique('roles', 'name')]
        ]);

        $role = Role::create($attribute);

        if (request()->expectsJson()) {
            return response()->json(['role' => $role]);
        }
    }

    public function destroy(Role $role)
    {
        $role->delete();
    }
}