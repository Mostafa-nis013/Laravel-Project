<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $defaults = [
        // General
        ['key' => 'store_name',        'value' => 'Velour Store',         'type' => 'string',  'group' => 'general',    'label' => 'Store Name',             'description' => 'The public name of your store.'],
        ['key' => 'store_email',       'value' => 'hello@velour.com',     'type' => 'string',  'group' => 'general',    'label' => 'Store Email',            'description' => 'Primary contact email.'],
        ['key' => 'store_phone',       'value' => '',                     'type' => 'string',  'group' => 'general',    'label' => 'Store Phone',            'description' => 'Customer support phone number.'],
        ['key' => 'store_address',     'value' => '',                     'type' => 'string',  'group' => 'general',    'label' => 'Store Address',          'description' => 'Physical address for invoices.'],
        ['key' => 'store_currency',    'value' => 'USD',                  'type' => 'string',  'group' => 'general',    'label' => 'Currency',               'description' => 'Store currency code (USD, EUR, GBP…).'],
        ['key' => 'tax_rate',          'value' => '8',                    'type' => 'integer', 'group' => 'general',    'label' => 'Tax Rate (%)',           'description' => 'Default tax rate applied to orders.'],
        // Store
        ['key' => 'free_shipping_min', 'value' => '100',                  'type' => 'integer', 'group' => 'store',      'label' => 'Free Shipping Minimum',  'description' => 'Order total above which shipping is free.'],
        ['key' => 'shipping_fee',      'value' => '9.99',                 'type' => 'string',  'group' => 'store',      'label' => 'Default Shipping Fee',   'description' => 'Flat shipping fee when below minimum.'],
        ['key' => 'low_stock_alert',   'value' => '5',                    'type' => 'integer', 'group' => 'store',      'label' => 'Low Stock Alert At',     'description' => 'Show warning when stock falls to this level.'],
        ['key' => 'allow_guest_order', 'value' => '0',                    'type' => 'boolean', 'group' => 'store',      'label' => 'Allow Guest Checkout',   'description' => 'Let customers order without an account.'],
        ['key' => 'store_maintenance', 'value' => '0',                    'type' => 'boolean', 'group' => 'store',      'label' => 'Maintenance Mode',       'description' => 'Take the storefront offline for maintenance.'],
        // Email
        ['key' => 'mail_from_name',    'value' => 'Velour',               'type' => 'string',  'group' => 'email',      'label' => 'Mail From Name',         'description' => 'Sender name on outgoing emails.'],
        ['key' => 'mail_from_address', 'value' => 'noreply@velour.com',   'type' => 'string',  'group' => 'email',      'label' => 'Mail From Address',      'description' => 'Sender address on outgoing emails.'],
        ['key' => 'order_confirm_email','value'=> '1',                    'type' => 'boolean', 'group' => 'email',      'label' => 'Order Confirmation',     'description' => 'Send email when a new order is placed.'],
        ['key' => 'shipping_notify',   'value' => '1',                    'type' => 'boolean', 'group' => 'email',      'label' => 'Shipping Notification',  'description' => 'Send email when order ships.'],
        // Appearance
        ['key' => 'items_per_page',    'value' => '15',                   'type' => 'integer', 'group' => 'appearance', 'label' => 'Items Per Page',         'description' => 'Default pagination size for admin lists.'],
        ['key' => 'show_out_of_stock', 'value' => '1',                    'type' => 'boolean', 'group' => 'appearance', 'label' => 'Show Out-of-Stock',      'description' => 'Display products with zero stock on storefront.'],
    ];

    public function index()
    {
        // Ensure all default keys exist
        foreach ($this->defaults as $d) {
            Setting::firstOrCreate(['key' => $d['key']], $d);
        }

        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($this->defaults as $d) {
            $key   = $d['key'];
            $type  = $d['type'];

            if ($type === 'boolean') {
                $value = $request->has($key) ? '1' : '0';
            } else {
                $value = $data[$key] ?? '';
            }

            Setting::set($key, $value);
        }

        ActivityLog::log('updated', 'Store settings were updated.');

        return redirect()->route('admin.settings')->with('success', 'Settings saved successfully.');
    }
}
