<?php

use Illuminate\Database\Seeder;

use App\Model\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'module' => 'merchant_apikey',
            'value' => '8b7fe885fe30fbc699e1a9860913fcbf'
        ]);
        Setting::create([
            'module' => 'merchant_code',
            'value' => 'D9015'
        ]);
        Setting::create([
            'module' => 'duitku_url',
            'value' => 'https://sandbox.duitku.com'
        ]);
    }
}
