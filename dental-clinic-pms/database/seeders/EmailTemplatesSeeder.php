<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::updateOrCreate([
            'type' => 'password_reset',
        ], [
            'name' => 'Password Reset',
            'subject' => 'Reset Your Password',
            'body' => '<p>Hello {{user_name}},</p><p>You are receiving this email because we received a password reset request for your account.</p><p><a href="{{reset_link}}">Reset Password</a></p><p>If you did not request a password reset, no further action is required.</p>',
        ]);

        EmailTemplate::updateOrCreate([
            'type' => 'stock_digest',
        ], [
            'name' => 'Inventory Stock Digest',
            'subject' => 'Inventory Digest: {{low_count}} low, {{expiring_count}} expiring',
            'body' => '<h2>Inventory Alerts</h2>'
                . '<div>{{low_stock_table}}</div>'
                . '<div style="margin-top:12px;">{{expiring_stock_table}}</div>'
                . '<p style="margin-top:12px;">Open Inventory: <a href="{{inventory_url}}">Inventory</a></p>',
        ]);

        EmailTemplate::updateOrCreate([
            'type' => 'stock_expiring',
        ], [
            'name' => 'Inventory Expiring Items',
            'subject' => 'Expiring Items: {{expiring_count}} item(s) nearing expiry',
            'body' => '<h2>Expiring Soon</h2>'
                . '<div>{{expiring_stock_table}}</div>'
                . '<p style="margin-top:12px;">Open Inventory: <a href="{{inventory_url}}">Inventory</a></p>',
        ]);
    }
}
