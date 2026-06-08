<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Electronics',   'description' => 'Gadgets and electronic devices'],
            ['name' => 'Clothing',      'description' => 'Fashion and apparel'],
            ['name' => 'Home & Garden', 'description' => 'Home decor and garden supplies'],
            ['name' => 'Sports',        'description' => 'Sports and outdoor equipment'],
            ['name' => 'Books',         'description' => 'Books and educational materials'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active'   => true,
                'sort_order'  => 0,
            ]);
        }

        // Products
        $products = [
            ['name' => 'Wireless Headphones',  'price' => 99.99,  'sale_price' => 79.99, 'stock' => 50,  'sku' => 'ELEC-001', 'category' => 'Electronics'],
            ['name' => 'Smartphone Stand',     'price' => 24.99,  'sale_price' => null,  'stock' => 100, 'sku' => 'ELEC-002', 'category' => 'Electronics'],
            ['name' => 'USB-C Hub',            'price' => 49.99,  'sale_price' => null,  'stock' => 75,  'sku' => 'ELEC-003', 'category' => 'Electronics'],
            ['name' => 'Classic T-Shirt',      'price' => 19.99,  'sale_price' => null,  'stock' => 200, 'sku' => 'CLO-001',  'category' => 'Clothing'],
            ['name' => 'Denim Jacket',         'price' => 89.99,  'sale_price' => 69.99, 'stock' => 30,  'sku' => 'CLO-002',  'category' => 'Clothing'],
            ['name' => 'Running Shoes',        'price' => 129.99, 'sale_price' => null,  'stock' => 40,  'sku' => 'CLO-003',  'category' => 'Clothing'],
            ['name' => 'Ceramic Plant Pot',    'price' => 34.99,  'sale_price' => null,  'stock' => 60,  'sku' => 'HOME-001', 'category' => 'Home & Garden'],
            ['name' => 'Garden Tool Set',      'price' => 59.99,  'sale_price' => 44.99, 'stock' => 25,  'sku' => 'HOME-002', 'category' => 'Home & Garden'],
            ['name' => 'Yoga Mat',             'price' => 39.99,  'sale_price' => null,  'stock' => 80,  'sku' => 'SPT-001',  'category' => 'Sports'],
            ['name' => 'Water Bottle',         'price' => 22.99,  'sale_price' => null,  'stock' => 150, 'sku' => 'SPT-002',  'category' => 'Sports'],
            ['name' => 'Laravel Up & Running', 'price' => 44.99,  'sale_price' => null,  'stock' => 0,   'sku' => 'BOOK-001', 'category' => 'Books'],
            ['name' => 'Clean Code',           'price' => 38.99,  'sale_price' => 29.99, 'stock' => 35,  'sku' => 'BOOK-002', 'category' => 'Books'],
        ];

        foreach ($products as $prod) {
            $category = Category::where('name', $prod['category'])->first();
            Product::create([
                'name'        => $prod['name'],
                'slug'        => Str::slug($prod['name']),
                'description' => "This is the {$prod['name']}. A high-quality product perfect for everyday use.",
                'price'       => $prod['price'],
                'sale_price'  => $prod['sale_price'],
                'stock'       => $prod['stock'],
                'sku'         => $prod['sku'],
                'category_id' => $category->id,
                'is_active'   => true,
                'is_featured' => in_array($prod['sku'], ['ELEC-001', 'CLO-002', 'SPT-001']),
            ]);
        }

        // Sample Orders
        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
        for ($i = 1; $i <= 8; $i++) {
            $order = Order::create([
                'customer_name'    => "Customer {$i}",
                'customer_email'   => "customer{$i}@example.com",
                'customer_phone'   => '+1 555-000-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'shipping_address' => "{$i} Main Street",
                'shipping_city'    => 'New York',
                'shipping_state'   => 'NY',
                'shipping_zip'     => '10001',
                'shipping_country' => 'US',
                'subtotal'         => 99.99 * $i,
                'shipping_fee'     => $i > 2 ? 0 : 9.99,
                'tax'              => round(99.99 * $i * 0.08, 2),
                'total'            => round(99.99 * $i * 1.08 + ($i > 2 ? 0 : 9.99), 2),
                'status'           => $statuses[($i - 1) % 4],
                'payment_method'   => 'credit_card',
                'payment_status'   => 'paid',
            ]);

            $product = Product::inRandomOrder()->first();
            $order->items()->create([
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'product_sku'  => $product->sku,
                'quantity'     => $i,
                'unit_price'   => $product->price,
                'subtotal'     => $product->price * $i,
            ]);
        }
    }
}
