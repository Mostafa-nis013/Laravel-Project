<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────────────────────────────
        $rolesData = [
            ['name' => Role::ADMIN,     'label' => 'Administrator', 'description' => 'Full access to everything including user management.'],
            ['name' => Role::EDITOR,    'label' => 'Editor',        'description' => 'Can manage products, categories, and orders.'],
            ['name' => Role::MODERATOR, 'label' => 'Moderator',     'description' => 'Can view and manage orders and products.'],
            ['name' => Role::USER,      'label' => 'User',          'description' => 'Standard customer account with storefront access only.'],
        ];

        foreach ($rolesData as $r) {
            Role::firstOrCreate(['name' => $r['name']], $r);
        }

        $adminRole     = Role::where('name', Role::ADMIN)->first();
        $editorRole    = Role::where('name', Role::EDITOR)->first();
        $moderatorRole = Role::where('name', Role::MODERATOR)->first();
        $userRole      = Role::where('name', Role::USER)->first();

        // ── Users ──────────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@velour.com'], [
            'name' => 'Admin User', 'password' => Hash::make('password'), 'is_active' => true,
        ]);
        $admin->assignRole($adminRole);

        $editor = User::firstOrCreate(['email' => 'editor@velour.com'], [
            'name' => 'Editor User', 'password' => Hash::make('password'), 'is_active' => true,
        ]);
        $editor->assignRole($editorRole);

        $moderator = User::firstOrCreate(['email' => 'moderator@velour.com'], [
            'name' => 'Moderator User', 'password' => Hash::make('password'), 'is_active' => true,
        ]);
        $moderator->assignRole($moderatorRole);

        $user = User::firstOrCreate(['email' => 'user@velour.com'], [
            'name' => 'Regular User', 'password' => Hash::make('password'), 'is_active' => true,
        ]);
        $user->assignRole($userRole);

        // ── Default Settings ───────────────────────────────────────────────────
        $defaults = [
            ['key' => 'store_name',        'value' => 'Velour Store',       'type' => 'string',  'group' => 'general',    'label' => 'Store Name',            'description' => 'The public name of your store.'],
            ['key' => 'store_email',       'value' => 'hello@velour.com',   'type' => 'string',  'group' => 'general',    'label' => 'Store Email',           'description' => 'Primary contact email.'],
            ['key' => 'store_currency',    'value' => 'USD',                'type' => 'string',  'group' => 'general',    'label' => 'Currency',              'description' => 'Store currency code.'],
            ['key' => 'tax_rate',          'value' => '8',                  'type' => 'integer', 'group' => 'general',    'label' => 'Tax Rate (%)',          'description' => 'Default tax rate.'],
            ['key' => 'free_shipping_min', 'value' => '100',                'type' => 'integer', 'group' => 'store',      'label' => 'Free Shipping Min',     'description' => 'Order total for free shipping.'],
            ['key' => 'shipping_fee',      'value' => '9.99',               'type' => 'string',  'group' => 'store',      'label' => 'Default Shipping Fee',  'description' => 'Flat shipping fee.'],
            ['key' => 'low_stock_alert',   'value' => '5',                  'type' => 'integer', 'group' => 'store',      'label' => 'Low Stock Alert At',    'description' => 'Warning threshold.'],
            ['key' => 'allow_guest_order', 'value' => '0',                  'type' => 'boolean', 'group' => 'store',      'label' => 'Allow Guest Checkout',  'description' => 'Let customers order without account.'],
            ['key' => 'store_maintenance', 'value' => '0',                  'type' => 'boolean', 'group' => 'store',      'label' => 'Maintenance Mode',      'description' => 'Take storefront offline.'],
            ['key' => 'mail_from_name',    'value' => 'Velour',             'type' => 'string',  'group' => 'email',      'label' => 'Mail From Name',        'description' => 'Sender name.'],
            ['key' => 'mail_from_address', 'value' => 'noreply@velour.com', 'type' => 'string',  'group' => 'email',      'label' => 'Mail From Address',     'description' => 'Sender address.'],
            ['key' => 'order_confirm_email','value'=> '1',                  'type' => 'boolean', 'group' => 'email',      'label' => 'Order Confirmation',    'description' => 'Email on new order.'],
            ['key' => 'shipping_notify',   'value' => '1',                  'type' => 'boolean', 'group' => 'email',      'label' => 'Shipping Notification', 'description' => 'Email on shipment.'],
            ['key' => 'items_per_page',    'value' => '15',                 'type' => 'integer', 'group' => 'appearance', 'label' => 'Items Per Page',        'description' => 'Pagination size.'],
            ['key' => 'show_out_of_stock', 'value' => '1',                  'type' => 'boolean', 'group' => 'appearance', 'label' => 'Show Out-of-Stock',     'description' => 'Show zero-stock products.'],
        ];

        foreach ($defaults as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }

        // ── Categories ─────────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Electronics',   'description' => 'Gadgets and electronic devices'],
            ['name' => 'Clothing',      'description' => 'Fashion and apparel'],
            ['name' => 'Home & Garden', 'description' => 'Home decor and garden supplies'],
            ['name' => 'Sports',        'description' => 'Sports and outdoor equipment'],
            ['name' => 'Books',         'description' => 'Books and educational materials'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], [
                'slug' => Str::slug($cat['name']), 'description' => $cat['description'],
                'is_active' => true, 'sort_order' => 0,
            ]);
        }

        // ── Products ───────────────────────────────────────────────────────────
        $products = [
            ['name'=>'Wireless Headphones',  'price'=>99.99,  'sale_price'=>79.99,'stock'=>50, 'sku'=>'ELEC-001','category'=>'Electronics'],
            ['name'=>'Smartphone Stand',     'price'=>24.99,  'sale_price'=>null, 'stock'=>100,'sku'=>'ELEC-002','category'=>'Electronics'],
            ['name'=>'USB-C Hub',            'price'=>49.99,  'sale_price'=>null, 'stock'=>75, 'sku'=>'ELEC-003','category'=>'Electronics'],
            ['name'=>'Classic T-Shirt',      'price'=>19.99,  'sale_price'=>null, 'stock'=>200,'sku'=>'CLO-001', 'category'=>'Clothing'],
            ['name'=>'Denim Jacket',         'price'=>89.99,  'sale_price'=>69.99,'stock'=>30, 'sku'=>'CLO-002', 'category'=>'Clothing'],
            ['name'=>'Running Shoes',        'price'=>129.99, 'sale_price'=>null, 'stock'=>40, 'sku'=>'CLO-003', 'category'=>'Clothing'],
            ['name'=>'Ceramic Plant Pot',    'price'=>34.99,  'sale_price'=>null, 'stock'=>60, 'sku'=>'HOME-001','category'=>'Home & Garden'],
            ['name'=>'Garden Tool Set',      'price'=>59.99,  'sale_price'=>44.99,'stock'=>25, 'sku'=>'HOME-002','category'=>'Home & Garden'],
            ['name'=>'Yoga Mat',             'price'=>39.99,  'sale_price'=>null, 'stock'=>80, 'sku'=>'SPT-001', 'category'=>'Sports'],
            ['name'=>'Water Bottle',         'price'=>22.99,  'sale_price'=>null, 'stock'=>150,'sku'=>'SPT-002', 'category'=>'Sports'],
            ['name'=>'Laravel Up & Running', 'price'=>44.99,  'sale_price'=>null, 'stock'=>0,  'sku'=>'BOOK-001','category'=>'Books'],
            ['name'=>'Clean Code',           'price'=>38.99,  'sale_price'=>29.99,'stock'=>35, 'sku'=>'BOOK-002','category'=>'Books'],
        ];

        foreach ($products as $prod) {
            $category = Category::where('name', $prod['category'])->first();
            Product::firstOrCreate(['sku' => $prod['sku']], [
                'name'        => $prod['name'],
                'slug'        => Str::slug($prod['name']),
                'description' => "High-quality {$prod['name']} — perfect for everyday use.",
                'price'       => $prod['price'],
                'sale_price'  => $prod['sale_price'],
                'stock'       => $prod['stock'],
                'category_id' => $category->id,
                'is_active'   => true,
                'is_featured' => in_array($prod['sku'], ['ELEC-001', 'CLO-002', 'SPT-001']),
            ]);
        }

        // ── Sample Orders ──────────────────────────────────────────────────────
        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
        for ($i = 1; $i <= 12; $i++) {
            $daysAgo = rand(0, 30);
            $order = Order::create([
                'customer_name'    => "Customer {$i}",
                'customer_email'   => "customer{$i}@example.com",
                'customer_phone'   => '+1 555-000-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'shipping_address' => "{$i} Main Street",
                'shipping_city'    => 'New York', 'shipping_state' => 'NY',
                'shipping_zip'     => '10001',    'shipping_country' => 'US',
                'subtotal'         => 99.99 * $i,
                'shipping_fee'     => $i > 2 ? 0 : 9.99,
                'tax'              => round(99.99 * $i * 0.08, 2),
                'total'            => round(99.99 * $i * 1.08 + ($i > 2 ? 0 : 9.99), 2),
                'status'           => $statuses[($i - 1) % 4],
                'payment_method'   => 'credit_card',
                'payment_status'   => 'paid',
                'created_at'       => now()->subDays($daysAgo),
                'updated_at'       => now()->subDays($daysAgo),
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

        // ── Sample Activity Logs ───────────────────────────────────────────────
        $activitySamples = [
            ['user_id' => $admin->id,     'action' => 'login',   'description' => 'Signed in to admin panel.'],
            ['user_id' => $admin->id,     'action' => 'created', 'description' => 'Created product "Wireless Headphones".', 'model_type' => 'App\Models\Product', 'model_label' => 'Wireless Headphones'],
            ['user_id' => $editor->id,    'action' => 'login',   'description' => 'Signed in to admin panel.'],
            ['user_id' => $editor->id,    'action' => 'updated', 'description' => 'Updated product "Denim Jacket".', 'model_type' => 'App\Models\Product', 'model_label' => 'Denim Jacket'],
            ['user_id' => $admin->id,     'action' => 'created', 'description' => 'Created category "Electronics".', 'model_type' => 'App\Models\Category', 'model_label' => 'Electronics'],
            ['user_id' => $moderator->id, 'action' => 'login',   'description' => 'Signed in to admin panel.'],
            ['user_id' => $moderator->id, 'action' => 'status',  'description' => 'Changed order status to Shipped.', 'model_type' => 'App\Models\Order', 'model_label' => 'ORD-SAMPLE'],
            ['user_id' => $admin->id,     'action' => 'updated', 'description' => 'Updated store settings.'],
            ['user_id' => $editor->id,    'action' => 'created', 'description' => 'Created new order for Customer 1.', 'model_type' => 'App\Models\Order', 'model_label' => 'ORD-SAMPLE2'],
            ['user_id' => $admin->id,     'action' => 'created', 'description' => 'Created user "Editor User".', 'model_type' => 'App\Models\User', 'model_label' => 'Editor User'],
        ];

        foreach ($activitySamples as $i => $entry) {
            ActivityLog::create(array_merge($entry, [
                'model_id'   => null,
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subHours($i * 2 + rand(0, 3)),
                'updated_at' => now()->subHours($i * 2),
            ]));
        }
    }
}
