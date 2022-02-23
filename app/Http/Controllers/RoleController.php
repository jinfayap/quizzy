<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function store()
    {
        $attribute = request()->validate([
            'name' => ['required', Rule::unique('roles', 'name')]
        ]);

        Role::create($attribute);
    }

    public function destroy(Role $role)
    {
        $role->delete();
    }
}