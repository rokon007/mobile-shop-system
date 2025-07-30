<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // Shop Information
            ['key' => 'shop_name', 'value' => 'Mobile Shop Management System', 'type' => 'text', 'group' => 'shop'],
            ['key' => 'shop_address', 'value' => 'Dhaka, Bangladesh', 'type' => 'textarea', 'group' => 'shop'],
            ['key' => 'shop_phone', 'value' => '01712345678', 'type' => 'text', 'group' => 'shop'],
            ['key' => 'shop_email', 'value' => 'info@mobileshop.com', 'type' => 'email', 'group' => 'shop'],
            ['key' => 'shop_logo', 'value' => '', 'type' => 'file', 'group' => 'shop'],
            
            // Currency Settings
            ['key' => 'currency_symbol', 'value' => '৳', 'type' => 'text', 'group' => 'currency'],
            ['key' => 'currency_position', 'value' => 'before', 'type' => 'select', 'group' => 'currency'],
            ['key' => 'decimal_places', 'value' => '2', 'type' => 'number', 'group' => 'currency'],
            
            // Tax Settings
            ['key' => 'default_tax_rate', 'value' => '0', 'type' => 'number', 'group' => 'tax'],
            ['key' => 'tax_inclusive', 'value' => 'false', 'type' => 'boolean', 'group' => 'tax'],
            
            // SMS Settings
            ['key' => 'sms_gateway', 'value' => 'nexmo', 'type' => 'select', 'group' => 'sms'],
            ['key' => 'sms_api_key', 'value' => '', 'type' => 'text', 'group' => 'sms'],
            ['key' => 'sms_api_secret', 'value' => '', 'type' => 'text', 'group' => 'sms'],
            ['key' => 'sms_from', 'value' => 'MobileShop', 'type' => 'text', 'group' => 'sms'],
            
            // Email Settings
            ['key' => 'mail_driver', 'value' => 'smtp', 'type' => 'select', 'group' => 'email'],
            ['key' => 'mail_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group' => 'email'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'number', 'group' => 'email'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text', 'group' => 'email'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password', 'group' => 'email'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'select', 'group' => 'email'],
            
            // Backup Settings
            ['key' => 'auto_backup', 'value' => 'true', 'type' => 'boolean', 'group' => 'backup'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'type' => 'select', 'group' => 'backup'],
            ['key' => 'backup_retention_days', 'value' => '30', 'type' => 'number', 'group' => 'backup'],
            
            // Notification Settings
            ['key' => 'low_stock_alert', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'payment_reminder', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'sale_notification', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
