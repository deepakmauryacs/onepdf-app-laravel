<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['iso' => 'US', 'name' => 'United States', 'iso3' => 'USA', 'numcode' => 840, 'phonecode' => 1],
            ['iso' => 'CA', 'name' => 'Canada',        'iso3' => 'CAN', 'numcode' => 124, 'phonecode' => 1],
            ['iso' => 'GB', 'name' => 'United Kingdom','iso3' => 'GBR', 'numcode' => 826, 'phonecode' => 44],
            ['iso' => 'AU', 'name' => 'Australia',     'iso3' => 'AUS', 'numcode' => 36,  'phonecode' => 61],
            ['iso' => 'IN', 'name' => 'India',         'iso3' => 'IND', 'numcode' => 356, 'phonecode' => 91],
            ['iso' => 'DE', 'name' => 'Germany',       'iso3' => 'DEU', 'numcode' => 276, 'phonecode' => 49],
            ['iso' => 'FR', 'name' => 'France',        'iso3' => 'FRA', 'numcode' => 250, 'phonecode' => 33],
            ['iso' => 'JP', 'name' => 'Japan',         'iso3' => 'JPN', 'numcode' => 392, 'phonecode' => 81],
            ['iso' => 'CN', 'name' => 'China',         'iso3' => 'CHN', 'numcode' => 156, 'phonecode' => 86],
            ['iso' => 'BR', 'name' => 'Brazil',        'iso3' => 'BRA', 'numcode' => 76,  'phonecode' => 55],
        ];

        DB::table('countries')->insert($countries);
    }
}
