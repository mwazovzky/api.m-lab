<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_filter_users_by_role()
    {
        $role = factory(Role::class)->create(['name' => 'admin', 'description' => 'Administrator']);
        $user = factory(User::class)->create();
        factory(User::class, 7)->create();
        factory(User::class, 2)->create(['role_id' => $role->id]);

        $response = $this->actingAs($user, 'api')->json('GET', '/api/users', [
            'role' => 'admin',
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    /**
     * @test
     */
    public function it_can_filter_users_by_roles()
    {
        $role = factory(Role::class)->create(['name' => 'admin', 'description' => 'Administrator']);
        $user = factory(User::class)->create();
        factory(User::class, 7)->create();
        factory(User::class, 2)->create(['role_id' => $role->id]);

        $response = $this->actingAs($user, 'api')->json('GET', '/api/users', [
            'roles' => [$role->id],
        ]);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }
}
