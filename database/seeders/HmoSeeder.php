<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HmoSeeder extends Seeder
{
    private $hmos = [
        ['name'=>'HMO A', 'code'=> 'HMO-A', 'email' => 'hmo.a@curacel.com'],
        ['name'=>'HMO B', 'code'=> 'HMO-B', 'email' => 'hmo.b@curacel.com'],
        ['name'=>'HMO C', 'code'=> 'HMO-C', 'email' => 'hmo.c@curacel.com'],
        ['name'=>'HMO D', 'code'=> 'HMO-D', 'email' => 'hmo.d@curacel.com'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hmos')->insert($this->hmos);
    }
}
