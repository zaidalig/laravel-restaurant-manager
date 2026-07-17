<?php

namespace Tests\Feature;

use App\Models\RestaurantOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_waiter_can_close_bill_with_payment(): void
    {
        $user = User::create([
            'name' => 'Waiter',
            'email' => 'waiter-pay@test.local',
            'password' => 'password',
            'role' => 'waiter',
            'status' => 'active',
        ]);

        $order = RestaurantOrder::create([
            'order_number' => 'ORD-00001',
            'status' => 'served',
            'subtotal' => 25.00,
            'tax' => 2.50,
            'total' => 27.50,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->patch("/orders/{$order->id}/pay", ['payment_method' => 'cash'])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertSame('cash', $order->payment_method);
        $this->assertNotNull($order->paid_at);
    }

    public function test_viewer_cannot_close_bill(): void
    {
        $viewer = User::create([
            'name' => 'Viewer',
            'email' => 'viewer-pay@test.local',
            'password' => 'password',
            'role' => 'viewer',
            'status' => 'active',
        ]);

        $order = RestaurantOrder::create([
            'order_number' => 'ORD-00002',
            'status' => 'open',
            'subtotal' => 10.00,
            'tax' => 0,
            'total' => 10.00,
        ]);

        $this->actingAs($viewer)
            ->patch("/orders/{$order->id}/pay", ['payment_method' => 'card'])
            ->assertForbidden();
    }
}
