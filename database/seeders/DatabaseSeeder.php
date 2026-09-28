<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pistis.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create sample customer
        Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+234 800 000 0000',
            'password' => Hash::make('password'),
        ]);

        // Create categories
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Gadgets and devices']);
        $clothing = Category::create(['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel']);
        $accessories = Category::create(['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Phone cases, bags, and more']);
        $home = Category::create(['name' => 'Home & Living', 'slug' => 'home-living', 'description' => 'Home decor and essentials']);

        // Create sample products
        $products = [
            ['name' => 'Wireless Bluetooth Headphones', 'category_id' => $electronics->id, 'price' => 25000, 'compare_price' => 35000, 'stock_quantity' => 50, 'featured' => true, 'description' => 'Premium noise-canceling wireless headphones with 40-hour battery life. Crystal clear audio with deep bass response.'],
            ['name' => 'Smart Watch Pro', 'category_id' => $electronics->id, 'price' => 45000, 'compare_price' => 60000, 'stock_quantity' => 30, 'featured' => true, 'description' => 'Advanced smartwatch with health tracking, GPS, and AMOLED display. Water-resistant up to 50m.'],
            ['name' => 'USB-C Fast Charger', 'category_id' => $electronics->id, 'price' => 8500, 'stock_quantity' => 100, 'description' => '65W GaN USB-C fast charger compatible with laptops, phones, and tablets.'],
            ['name' => 'Premium Cotton T-Shirt', 'category_id' => $clothing->id, 'price' => 5000, 'compare_price' => 7500, 'stock_quantity' => 200, 'featured' => true, 'description' => '100% organic cotton premium t-shirt. Available in multiple colors. Comfortable everyday wear.'],
            ['name' => 'Classic Denim Jacket', 'category_id' => $clothing->id, 'price' => 18000, 'compare_price' => 25000, 'stock_quantity' => 40, 'description' => 'Timeless denim jacket with modern fit. Perfect for layering in any season.'],
            ['name' => 'Leather Wallet', 'category_id' => $accessories->id, 'price' => 12000, 'stock_quantity' => 75, 'featured' => true, 'description' => 'Genuine leather bifold wallet with RFID protection. Multiple card slots and bill compartment.'],
            ['name' => 'Phone Case - Premium', 'category_id' => $accessories->id, 'price' => 3500, 'stock_quantity' => 150, 'description' => 'Military-grade protection phone case with premium matte finish. Slim profile with raised camera guard.'],
            ['name' => 'Scented Candle Set', 'category_id' => $home->id, 'price' => 8000, 'compare_price' => 12000, 'stock_quantity' => 60, 'description' => 'Set of 3 artisan soy wax candles. Fragrances: Lavender, Vanilla, and Sandalwood. 45-hour burn time each.'],
            ['name' => 'Minimalist Desk Lamp', 'category_id' => $home->id, 'price' => 15000, 'stock_quantity' => 35, 'featured' => true, 'description' => 'LED desk lamp with 5 brightness levels and 3 color temperatures. USB charging port built-in.'],
            ['name' => 'Ceramic Mug Collection', 'category_id' => $home->id, 'price' => 4500, 'stock_quantity' => 90, 'description' => 'Set of 4 handcrafted ceramic mugs. Microwave and dishwasher safe. 350ml capacity.'],
            ['name' => 'Portable Bluetooth Speaker', 'category_id' => $electronics->id, 'price' => 18500, 'compare_price' => 22000, 'stock_quantity' => 45, 'featured' => true, 'description' => 'Waterproof portable speaker with 360° sound. 20-hour battery life with built-in microphone.'],
            ['name' => 'Canvas Tote Bag', 'category_id' => $accessories->id, 'price' => 6000, 'stock_quantity' => 80, 'description' => 'Heavy-duty canvas tote bag with reinforced handles. Perfect for daily use or shopping.'],
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, [
                'slug' => \Illuminate\Support\Str::slug($productData['name']),
                'sku' => 'PIS-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'status' => 'active',
                'shopify_sync_enabled' => true,
            ]));
        }

        // Default settings
        Setting::set('store_name', 'Pistis');
        Setting::set('store_email', 'hello@pistis.com');
        Setting::set('currency_symbol', '$');
        Setting::set('currency_code', 'USD');
        Setting::set('active_payment_gateway', 'paypal');

    }
}
