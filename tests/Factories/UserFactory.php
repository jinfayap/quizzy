<?php

namespace Tests\Factories;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserFactory
{
    public $role = null;

    public $permissions = null;

    public function withRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function withPermissions(array $permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function create()
    {
        $user = User::factory()->create();

        if ($this->role) {
            $role = Role::create(['name' => $this->role]);
        }

        if ($this->permissions) {
            foreach ($this->permissions as $permission) {
                $permission = Permission::create(['name' => $permission]);
                $role->givePermissionTo($permission);
            }
        }

        $user->assignRole($role);

        return $user;
    }
}