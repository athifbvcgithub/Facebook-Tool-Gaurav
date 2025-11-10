<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdAccount;
use App\Models\FacebookPage;
use App\Models\FacebookPixel;
use App\Models\Preset;

class AdLauncherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample ad accounts
        AdAccount::updateOrCreate(
            ['account_id' => 'act_123456789'],
            [
                'provider' => 'facebook',
                'name' => 'Test Facebook Ad Account',
                'currency' => 'INR',
                'timezone' => 'Asia/Kolkata',
                'is_active' => true,
            ]
        );

        AdAccount::updateOrCreate(
            ['account_id' => 'act_987654321'],
            [
                'provider' => 'facebook',
                'name' => 'BVC Services Ad Account',
                'currency' => 'INR',
                'timezone' => 'Asia/Kolkata',
                'is_active' => true,
            ]
        );

        // Create sample Facebook pages
        FacebookPage::updateOrCreate(
            ['page_id' => '111111111'],
            [
                'name' => 'Test Facebook Page 1',
                'is_active' => true,
            ]
        );

        FacebookPage::updateOrCreate(
            ['page_id' => '222222222'],
            [
                'name' => 'BVC Services Page',
                'is_active' => true,
            ]
        );

        // Create sample pixels
        FacebookPixel::updateOrCreate(
            ['pixel_id' => '333333333'],
            [
                'name' => 'Test Pixel 1',
                'ad_account_id' => 'act_123456789',
                'is_active' => true,
            ]
        );

        FacebookPixel::updateOrCreate(
            ['pixel_id' => '444444444'],
            [
                'name' => 'Test Pixel 2',
                'ad_account_id' => 'act_123456789',
                'is_active' => true,
            ]
        );

        FacebookPixel::updateOrCreate(
            ['pixel_id' => '555555555'],
            [
                'name' => 'BVC Pixel',
                'ad_account_id' => 'act_987654321',
                'is_active' => true,
            ]
        );

        // Create sample presets
        Preset::updateOrCreate(
            ['name' => 'E-commerce Sales Campaign'],
            [
                'configuration' => [
                    'objective' => 'OUTCOME_SALES',
                    'daily_budget' => 10000,
                    'billing_event' => 'IMPRESSIONS',
                    'optimization_goal' => 'CONVERSIONS',
                ],
                'is_active' => true,
            ]
        );

        Preset::updateOrCreate(
            ['name' => 'Lead Generation Campaign'],
            [
                'configuration' => [
                    'objective' => 'OUTCOME_LEADS',
                    'daily_budget' => 5000,
                    'billing_event' => 'IMPRESSIONS',
                    'optimization_goal' => 'LEAD',
                ],
                'is_active' => true,
            ]
        );

        Preset::updateOrCreate(
            ['name' => 'Traffic Campaign'],
            [
                'configuration' => [
                    'objective' => 'OUTCOME_TRAFFIC',
                    'daily_budget' => 3000,
                    'billing_event' => 'LINK_CLICKS',
                    'optimization_goal' => 'LINK_CLICKS',
                ],
                'is_active' => true,
            ]
        );

        $this->command->info('✓ Ad Launcher seed data created successfully!');
    }
}