<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function store()
    {
        $attribute = request()->validate([
            'name' => ['required', Rule::unique('permissions', 'name')]
        ]);

        Permission::create($attribute);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
    }
}