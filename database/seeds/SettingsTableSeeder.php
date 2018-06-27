<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
        	[
        		'id' => 1,
	            'key' => 'version',
	            'value' => '0.0.1',
	            'entity_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
	        	'id' => 2,
	            'key' => 'default_language',
	            'value' => 1,
	            'entity_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
        		'id' => 3,
	            'key' => 'version',
	            'value' => '0.0.1',
	            'entity_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
	        	'id' => 4,
	            'key' => 'default_language',
	            'value' => 2,
	            'entity_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ]
        ]);
    }
}
