<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EntityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entities')->insert([
        	[
	            'id' => 1,
	            'name' => 'Job Seeker App',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
	        	'id' => 2,
	        	'name' => 'Job Seeker Test App',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ]
        ]);
    }
}
