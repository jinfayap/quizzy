<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Facades\Tests\Factories\UserFactory;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_role_with_create_role_can_create_new_role()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['create role'])
            ->create();

        $this->actingAs($user)
            ->post('/role', $attribute = ['name' => 'educator']);

        $this->assertDatabaseHas('roles', $attribute);
    }

    /** @test */
    public function user_role_without_create_role_permission_cannot_create_new_role()
    {
        $user = UserFactory::withRole('educator')
            ->create();

        $this->actingAs($user)
            ->post('/role', $attribute = ['name' => 'assistant'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('roles', $attribute);
    }

    /** @test */
    public function guest_cannot_add_new_role()
    {
        $this->post('/role', $attribute = ['name' => 'educator'])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('roles', $attribute);
    }

    /** @test */
    public function only_unique_role_can_be_created()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['create role'])
            ->create();

        $this->actingAs($user)
            ->post('/role', ['name' => 'admin'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('roles', 1);
    }

    /** @test */
    public function user_role_with_delete_role_permission_can_delete_role()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['delete role'])
            ->create();

        $roleToDelete = Role::create(['name' => 'educator']);

        $this->assertDatabaseCount('roles', 2);

        $this->actingAs($user)
            ->delete("/role/{$roleToDelete->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseCount('roles', 1);
    }

    /** @test */
    public function user_role_without_delete_role_permission_cannot_delete_role()
    {
        $user = UserFactory::withRole('educator')
        ->create();

        $roleToDelete = Role::create(['name' => 'admin']);

        $this->actingAs($user)
            ->delete("/role/{$roleToDelete->getRouteKey()}")
            ->assertStatus(403);

        $this->assertDatabaseCount('roles', 2);
    }

    /** @test */
    public function guest_canot_delete_role()
    {
        $roleToDelete = Role::create(['name' => 'admin']);

        $this->delete("/role/{$roleToDelete->getRouteKey()}")->assertRedirect(route('login'));

        $this->assertDatabaseCount('roles', 1);
    }

    /** @test */
    public function when_a_role_is_deleted_the_associated_permissions_are_removed()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['delete role'])
            ->create();

        $role = $user->roles->where('name', 'admin')->first();

        $this->actingAs($user)
            ->delete("/role/{$role->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseCount('roles', 0);

        $this->assertDatabaseCount('permissions', 1);

        $this->assertDatabaseCount('role_has_permissions', 0);
    }

    /** @test */
    public function user_role_with_create_permission_can_create_new_permission()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['create permission'])
            ->create();

        $this->actingAs($user)
            ->post('/permission', $attribute = ['name' => 'delete permission'])
            ->assertStatus(200);

        $this->assertDatabaseHas('permissions', $attribute);
    }

    /** @test */
    public function user_role_without_create_permission_cannot_create_new_permission()
    {
        $user = UserFactory::withRole('educator')->create();

        $this->actingAs($user)
            ->post('/permission', $attribute = ['name' => 'delete permission'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('permissions', $attribute);
    }

    /** @test */
    public function guest_cannot_create_new_permission()
    {
        $this->post('/permission', $attribute = ['name' => 'delete permission'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('permissions', $attribute);
    }

    /** @test */
    public function only_unique_permission_can_be_created()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['create permission'])
            ->create();

        $this->actingAs($user)
            ->post('/permission', ['name' => 'create permission'])
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('permissions', 1);
    }

    /** @test */
    public function user_role_with_delete_permission_can_delete_permission()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['delete permission'])
            ->create();

        $permissionToDelete = Permission::create(['name' => 'create permission']);

        $this->assertDatabaseCount('permissions', 2);

        $this->actingAs($user)
            ->delete("/permission/{$permissionToDelete->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseCount('permissions', 1);
    }

    /** @test */
    public function user_role_without_delete_permission_cannot_delete_permission()
    {
        $user = UserFactory::withRole('admin')->create();

        $permissionToDelete = Permission::create(['name' => 'create permission']);

        $this->actingAs($user)
            ->delete("/permission/{$permissionToDelete->getRouteKey()}")
            ->assertStatus(403);

        $this->assertDatabaseCount('permissions', 1);
    }

    /** @test */
    public function guest_cannot_delete_permission()
    {
        $permissionToDelete = Permission::create(['name' => 'delete permission']);

        $this->delete("/permission/{$permissionToDelete->getRouteKey()}")->assertRedirect(route('login'));

        $this->assertDatabaseCount('permissions', 1);
    }

    /** @test */
    public function when_a_permission_is_deleted_the_associated_permissions_are_removed()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['delete permission'])
            ->create();

        $permission = $user->roles->where('name', 'admin')->first()
            ->permissions->where('name', 'delete permission')->first();

        $this->actingAs($user)
            ->delete("/permission/{$permission->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseCount('roles', 1);

        $this->assertDatabaseCount('permissions', 0);

        $this->assertDatabaseCount('role_has_permissions', 0);
    }

    /** @test */
    public function user_with_assign_role_permission_can_assign_permission_to_role()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['assign role permission'])
            ->create();

        $role = $user->roles->where('name', 'admin')->first();

        $permission = Permission::create(['name' => 'remove role permission']);

        $this->assertDatabaseCount('role_has_permissions', 1);

        $this->actingAs($user)
            ->post("/role/{$role->getRouteKey()}/permission/{$permission->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseCount('role_has_permissions', 2);
    }

    /** @test */
    public function user_role_without_assign_role_permission_cannot_assign_permission_to_role()
    {
        $user = UserFactory::withRole('admin')->create();

        $role = $user->roles->where('name', 'admin')->first();

        $permission = Permission::create(['name' => 'remove role permission']);

        $this->assertDatabaseCount('role_has_permissions', 0);

        $this->post("/role/{$role->getRouteKey()}/permission/{$permission->getRouteKey()}");

        $this->assertDatabaseCount('role_has_permissions', 0);
    }

    /** @test */
    public function guest_cannot_assign_permission_to_role()
    {
        $role = Role::create(['name' => 'admin']);

        $anotherPermission = Permission::create(['name' => 'remove role permission']);

        $this->post("/role/{$role->getRouteKey()}/permission/{$anotherPermission->getRouteKey()}")
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('role_has_permissions', 0);
    }

    /** @test */
    public function user_role_with_remove_role_permission_can_remove_permission_from_role()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['remove role permission', 'assign role permission'])
            ->create();

        $role = $user->roles->where('name', 'admin')->first();

        $permission = $role->permissions->where('name', 'assign role permission')->first();

        $this->assertDatabaseCount('role_has_permissions', 2);

        $this->actingAs($user)
            ->delete("/role/{$role->getRouteKey()}/permission/{$permission->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseCount('role_has_permissions', 1);
    }

    /** @test */
    public function user_role_without_remove_role_permission_cannot_remove_permission_from_role()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['assign role permission'])
            ->create();

        $role = $user->roles->where('name', 'admin')->first();

        $permission = $role->permissions->where('name', 'assign role permission')->first();

        $this->assertDatabaseCount('role_has_permissions', 1);

        $this->actingAs($user)
            ->delete("/role/{$role->getRouteKey()}/permission/{$permission->getRouteKey()}")
            ->assertStatus(403);

        $this->assertDatabaseCount('role_has_permissions', 1);
    }

    /** @test */
    public function guest_cannot_remove_permission_from_role()
    {
        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'assign role permission']);

        $role->givePermissionTo($permission);

        $this->assertDatabaseCount('role_has_permissions', 1);

        $this->delete("/role/{$role->getRouteKey()}/permission/{$permission->getRouteKey()}")
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('role_has_permissions', 1);
    }

    /** @test */
    public function user_role_with_assign_user_role_permission_can_assign_role_to_another_user()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['assign user role'])
            ->create();

        $role = $user->roles->where('name', 'admin')->first();

        $anotherUser = User::factory()->create();

        $this->actingAs($user)
            ->post("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}")
            ->assertStatus(200);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function user_role_without_assign_user_role_permission_cannot_assign_role_to_another_user()
    {
        $user = UserFactory::withRole('admin')->create();

        $role = $user->roles->where('name', 'admin')->first();

        $anotherUser = User::factory()->create();

        $this->actingAs($user)
            ->post("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}")
            ->assertStatus(403);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function guest_cannot_assign_role_to_another_user()
    {
        $role = Role::create(['name' => 'admin']);

        $anotherUser = User::factory()->create();

        $this->post("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}")
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function user_role_with_remove_user_role_permission_can_remove_role_from_another_user()
    {
        $user = UserFactory::withRole('admin')
            ->withPermissions(['remove user role'])
            ->create();

        $role = $user->roles->where('name', 'admin')->first();

        $anotherUser = User::factory()->create();

        $anotherUser->assignRole($role);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);

        $this->actingAs($user)
            ->delete("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}");

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function user_role_without_remove_user_role_permission_cannot_remove_role_from_another_user()
    {

        $user = UserFactory::withRole('admin')->create();

        $anotherUser = UserFactory::withRole('educator')->create();

        $role = $anotherUser->roles->where('name', 'educator')->first();

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);

        $this->actingAs($user)
            ->delete("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}")
            ->assertStatus(403);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function guest_cannot_remove_role_from_another_user()
    {
        $anotherUser = UserFactory::withRole('educator')->create();

        $role = $anotherUser->roles->where('name', 'educator')->first();

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);

        $this->delete("/user/{$anotherUser->getRouteKey()}/role/{$role->getRouteKey()}")
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $role->id,
            'model_id' => $anotherUser->id,
            'model_type' => get_class($anotherUser)
        ]);
    }

    /** @test */
    public function guest_cannot_vist_any_admin_panel_pages()
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/admin/role')->assertRedirect(route('login'));
        $this->get('/admin/permission')->assertRedirect(route('login'));
        $this->get('/admin/role-permission')->assertRedirect(route('login'));
        $this->get('/admin/user-role-permission')->assertRedirect(route('login'));
    }

    /** @test */
    public function user_without_view_admin_panel_cannot_visit_any_admin_panel_pages()
    {
        $this->signIn();
        $this->get('/admin')->assertStatus(403);
        $this->get('/admin/role')->assertStatus(403);
        $this->get('/admin/permission')->assertStatus(403);
        $this->get('/admin/role-permission')->assertStatus(403);
        $this->get('/admin/user-role-permission')->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_vist_any_api_pages()
    {
        $this->get('/api/role')->assertStatus(403);
        $this->get('/api/permission')->assertStatus(403);
        $this->get('/api/role-permission')->assertStatus(403);
        $this->get('/api/user-role-permission')->assertStatus(403);
    }

    /** @test */
    public function user_cannot_vist_any_api_pages()
    {
        $user = $this->signIn();

        $this->get('/api/role')->assertStatus(403);
        $this->get('/api/permission')->assertStatus(403);
        $this->get('/api/role-permission')->assertStatus(403);
        $this->get('/api/user-role-permission')->assertStatus(403);
    }
}