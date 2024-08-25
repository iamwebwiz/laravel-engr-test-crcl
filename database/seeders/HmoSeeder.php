<?php

namespace Database\Seeders;

use App\Modules\Batching\Enums\BatchingStrategyEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HmoSeeder extends Seeder
{
    private $hmos = [
        ['name'=>'HMO A', 'code'=> 'HMO-A', 'email' => 'hmo.a@curacel.com', 'batching_strategy' => BatchingStrategyEnum::SUBMISSION_DATE->value],
        ['name'=>'HMO B', 'code'=> 'HMO-B', 'email' => 'hmo.b@curacel.com', 'batching_strategy' => BatchingStrategyEnum::ENCOUNTER_DATE->value],
        ['name'=>'HMO C', 'code'=> 'HMO-C', 'email' => 'hmo.c@curacel.com', 'batching_strategy' => BatchingStrategyEnum::ENCOUNTER_DATE->value],
        ['name'=>'HMO D', 'code'=> 'HMO-D', 'email' => 'hmo.d@curacel.com', 'batching_strategy' => null],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hmos')->insert($this->hmos);
    }
}
