<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();

        return response()->json([
            'permissions' => $permissions
        ]);
    }

    public function store()
    {
        $attribute = request()->validate([
            'name' => ['required', Rule::unique('permissions', 'name')]
        ]);

        $permission = Permission::create($attribute);

        if (request()->expectsJson()) {
            return response()->json(['permission' => $permission]);
        }
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
    }
}