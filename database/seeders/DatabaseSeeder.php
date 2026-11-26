<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $staff = User::create([
            'username' => 'arcade.admin',
            'full_name' => 'Deinritze Monje',
            'password' => Hash::make('midnight123'),
        ]);

        User::create([
            'username' => 'floor.runner',
            'full_name' => 'Jules Rivera',
            'password' => Hash::make('midnight123'),
        ]);

        Product::insert([
            ['name' => 'Token Pack · 20 plays', 'price' => 180.00, 'stock_quantity' => 28, 'image' => 'arcade-hero.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Midnight Cola', 'price' => 85.00, 'stock_quantity' => 46, 'image' => 'arcade-floor.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Prize Counter Keychain', 'price' => 120.00, 'stock_quantity' => 5, 'image' => 'arcade-cabinet.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Extra Life Patch', 'price' => 150.00, 'stock_quantity' => 0, 'image' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $customer = Customer::create([
            'full_name' => 'Samira Cruz',
            'email' => 'samira.cruz@example.com',
            'phone' => '+63 917 555 0142',
        ]);

        $product = Product::where('name', 'Midnight Cola')->firstOrFail();
        $product->decrement('stock_quantity', 2);
        Sale::create([
            'product_id' => $product->id,
            'customer_id' => $customer->id,
            'sold_by' => $staff->id,
            'quantity' => 2,
            'total_price' => 170.00,
        ]);
    }
}
