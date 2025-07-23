<?php

namespace Database\Seeders;

use App\Models\ConfigSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'from_email'],
            ['value' => 'testsingh28@gmail.com'
        ]);
        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'host'],
            ['value' => 'smtp.gmail.com'
        ]);
        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'port'],
            ['value' => '587'
        ]);
        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'username'],
            ['value' => 'testsingh28@gmail.com'
        ]);
        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'from_name'],
            ['value' => 'LAUNDRY'
        ]);
        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'password'],
            ['value' => ''
        ]);
        ConfigSetting::updateOrCreate([
            'type' => 'smtp',
            'key' => 'encryption'],
            ['value' => 'tls'
        ]);
			
	
        // stripe
        ConfigSetting::updateOrCreate([
            'type' => 'stripe',
            'key' => 'STRIPE_KEY'],
            ['value' => ''
        ]); 
        ConfigSetting::updateOrCreate([
            'type' => 'stripe',
            'key' => 'STRIPE_SECRET'],
            ['value' => ''
        ]); 

        /*** Config setting*/

        ConfigSetting::updateOrCreate([
            'type' => 'config',
            'key' => 'CONFIG_MAX_WEIGHT'],
            ['value' => '10'
        ]); 

        // Delivery prices
        ConfigSetting::updateOrCreate([
            'type' => 'delivery-cost',
            'key' => 'DELIVERY_CHARGE'],[
            'value' => '15'
        ]); 

        ConfigSetting::updateOrCreate([
            'type' => 'delivery-cost',
            'key' => 'MINIMUM_ORDER_AMOUNT'],[
            'value' => '50'
        ]); 

        ConfigSetting::updateOrCreate([
            'type' => 'delivery-cost',
            'key' => 'FREE_DELIVERY'],[
            'value' => '200'
        ]); 


        // time-slot prices
        ConfigSetting::updateOrCreate([
            'type' => 'time-slot',
            'key' => 'TIME_SLOT'],[
            'value' => '30'
        ]); 

    }
}
