<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Restaurant Owner', 'email' => 'owner@example.com', 'password' => 'password', 'role' => 'owner', 'status' => 'active'],
            ['name' => 'Floor Manager', 'email' => 'manager@example.com', 'password' => 'password', 'role' => 'manager', 'status' => 'active'],
            ['name' => 'Host Alex', 'email' => 'host@example.com', 'password' => 'password', 'role' => 'host', 'status' => 'active'],
            ['name' => 'Waiter Sam', 'email' => 'waiter@example.com', 'password' => 'password', 'role' => 'waiter', 'status' => 'active'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        foreach ([
            ['name' => 'T1', 'capacity' => 2, 'zone' => 'Window'],
            ['name' => 'T2', 'capacity' => 4, 'zone' => 'Main Hall'],
            ['name' => 'T3', 'capacity' => 4, 'zone' => 'Main Hall'],
            ['name' => 'T4', 'capacity' => 6, 'zone' => 'Patio'],
            ['name' => 'T5', 'capacity' => 8, 'zone' => 'Private'],
        ] as $table) {
            DiningTable::create($table + ['status' => 'available']);
        }

        $categories = [
            ['name' => 'Starters', 'description' => 'Appetizers'],
            ['name' => 'Mains', 'description' => 'Main dishes'],
            ['name' => 'Desserts', 'description' => 'Sweet endings'],
            ['name' => 'Drinks', 'description' => 'Beverages'],
        ];

        foreach ($categories as $category) {
            MenuCategory::create($category + ['status' => 'active']);
        }

        $items = [
            [1, 'Garlic Bread', 5.99, 10, true],
            [1, 'Soup of the Day', 6.50, 12, true],
            [2, 'Grilled Chicken', 14.99, 20, false],
            [2, 'Pasta Alfredo', 13.50, 18, true],
            [2, 'Beef Steak', 22.99, 25, false],
            [3, 'Chocolate Cake', 7.50, 8, true],
            [4, 'Fresh Juice', 4.50, 5, true],
            [4, 'Espresso', 3.00, 3, true],
        ];

        foreach ($items as [$cat, $name, $price, $prep, $veg]) {
            MenuItem::create([
                'menu_category_id' => $cat,
                'name' => $name,
                'price' => $price,
                'prep_time' => $prep,
                'is_vegetarian' => $veg,
                'status' => 'active',
            ]);
        }

        Reservation::create([
            'dining_table_id' => 2,
            'customer_name' => 'Jane Cooper',
            'phone' => '+1 555 2001',
            'party_size' => 4,
            'reservation_date' => today(),
            'reservation_time' => '19:00:00',
            'status' => 'confirmed',
            'user_id' => 3,
        ]);

        StaffShift::create([
            'user_id' => 4,
            'shift_date' => today(),
            'start_time' => '17:00:00',
            'end_time' => '23:00:00',
            'position' => 'Waiter',
            'status' => 'scheduled',
        ]);
    }
}
