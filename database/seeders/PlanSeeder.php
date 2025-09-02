<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['id'=>1,'name'=>'Free',     'inr_price'=>'0.00',   'usd_price'=>'0.00',  'billing_cycle'=>'free'],
            ['id'=>2,'name'=>'Pro',      'inr_price'=>'12.00',  'usd_price'=>'12.00', 'billing_cycle'=>'month'],
            ['id'=>3,'name'=>'Pro',      'inr_price'=>'499.00', 'usd_price'=>'499.00','billing_cycle'=>'year'],
            ['id'=>4,'name'=>'Business', 'inr_price'=>'25.00',  'usd_price'=>'25.00', 'billing_cycle'=>'month'],
            ['id'=>5,'name'=>'Business', 'inr_price'=>'1999.00','usd_price'=>'1999.00','billing_cycle'=>'year'],
        ];

        foreach ($rows as $data) {
            // upsert by unique key (name + billing_cycle) to avoid duplicates on re-seed
            Plan::updateOrCreate(
                ['name' => $data['name'], 'billing_cycle' => $data['billing_cycle']],
                collect($data)->except(['id'])->toArray()
            );
        }
    }
}
