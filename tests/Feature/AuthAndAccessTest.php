<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_owner_can_view_dashboard(): void
    {
        $user = User::create([
            'name' => 'Owner',
            'email' => 'owner@test.local',
            'password' => 'password',
            'role' => 'owner',
            'status' => 'active',
        ]);

        $this->actingAs($user)->get('/')->assertOk();
    }

    public function test_owner_can_manage_restaurant_modules(): void
    {
        $user = User::create([
            'name' => 'Owner',
            'email' => 'owner-rest@test.local',
            'password' => 'password',
            'role' => 'owner',
            'status' => 'active',
        ]);

        $this->actingAs($user)->get('/tables')->assertOk();
        $this->actingAs($user)->get('/menu-items')->assertOk();
        $this->actingAs($user)->get('/users')->assertOk();
    }

    public function test_waiter_can_access_orders_but_not_tables(): void
    {
        $user = User::create([
            'name' => 'Waiter',
            'email' => 'waiter@test.local',
            'password' => 'password',
            'role' => 'waiter',
            'status' => 'active',
        ]);

        $this->actingAs($user)->get('/orders')->assertOk();
        $this->actingAs($user)->get('/tables')->assertForbidden();
    }

    public function test_host_can_manage_reservations(): void
    {
        $user = User::create([
            'name' => 'Host',
            'email' => 'host@test.local',
            'password' => 'password',
            'role' => 'host',
            'status' => 'active',
        ]);

        $this->actingAs($user)->get('/reservations')->assertOk();
        $this->actingAs($user)->get('/orders')->assertForbidden();
    }
}
