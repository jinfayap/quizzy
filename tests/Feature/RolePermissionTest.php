<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_with_add_role_permission_can_add_new_role()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'create role']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->post('/role', $attribute = ['name' => 'educator']);

        $this->assertDatabaseHas('roles', $attribute);
    }

    /** @test */
    public function user_without_add_role_permission_cannot_add_new_role()
    {
        $user = $this->signIn();

        $this->post('/role', $attribute = ['name' => 'educator'])->assertStatus(403);

        $this->assertDatabaseMissing('roles', $attribute);
    }

    /** @test */
    public function only_unique_role_can_be_created()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'create role']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->post('/role', ['name' => 'admin'])->assertSessionHasErrors('name');

        $this->assertDatabaseCount('roles', 1);
    }

    /** @test */
    public function user_with_delete_role_permission_can_delete_role()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'delete role']);

        $roleToDelete = Role::create(['name' => 'educator']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->assertDatabaseCount('roles', 2);

        $this->delete("/role/{$roleToDelete->getRouteKey()}");

        $this->assertDatabaseCount('roles', 1);
    }

    /** @test */
    public function when_a_role_is_deleted_the_associated_permissions_are_removed()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'delete role']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->delete("/role/{$role->getRouteKey()}");

        $this->assertDatabaseCount('roles', 0);

        $this->assertDatabaseCount('permissions', 1);

        $this->assertDatabaseCount('role_has_permissions', 0);
    }

    /** @test */
    public function user_with_add_permission_can_create_new_permission()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'create permission']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->post('/permission', $attribute = ['name' => 'delete permission']);

        $this->assertDatabaseHas('permissions', $attribute);
    }

    /** @test */
    public function only_unique_permission_can_be_created()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'create permission']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->post('/permission', ['name' => 'create permission']);

        $this->assertDatabaseCount('permissions', 1);
    }

    /** @test */
    public function user_with_delete_permission_can_delete_permission()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'delete permission']);

        $permissionToDelete = Permission::create(['name' => 'create permission']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->assertDatabaseCount('permissions', 2);

        $this->delete("/permission/{$permissionToDelete->getRouteKey()}");

        $this->assertDatabaseCount('permissions', 1);
    }

    /** @test */
    public function when_a_permission_is_deleted_the_associated_permissions_are_removed()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'delete permission']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->delete("/permission/{$permission->getRouteKey()}");

        $this->assertDatabaseCount('roles', 1);

        $this->assertDatabaseCount('permissions', 0);

        $this->assertDatabaseCount('role_has_permissions', 0);
    }

    /** @test */
    public function user_with_assign_role_permission_can_assign_permission_to_role()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'assign role permission']);

        $anotherPermission = Permission::create(['name' => 'remove role permission']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $this->assertDatabaseCount('role_has_permissions', 1);

        $this->post("/role/{$role->getRouteKey()}/permission/{$anotherPermission->getRouteKey()}");

        $this->assertDatabaseCount('role_has_permissions', 2);
    }

    /** @test */
    public function user_with_remove_role_permission_can_remove_permission_from_role()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'remove role permission']);

        $anotherPermission = Permission::create(['name' => 'assign role permission']);

        $role->givePermissionTo($permission);

        $role->givePermissionTo($anotherPermission);

        $user->assignRole($role);

        $this->assertDatabaseCount('role_has_permissions', 2);

        $this->delete("/role/{$role->getRouteKey()}/permission/{$anotherPermission->getRouteKey()}");

        $this->assertDatabaseCount('role_has_permissions', 1);
    }

    /** @test */
    public function user_with_assign_role_permission_can_assign_role_to_another_user()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'assign user role']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $anotherUser = User::factory()->create();

        $this->post("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}");

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function user_with_remove_role_permission_can_remove_role_from_another_user()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'remove user role']);

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        $anotherUser = User::factory()->create();

        $anotherUser->assignRole($role);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);

        $this->delete("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}");

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }
}