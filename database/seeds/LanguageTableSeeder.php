<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
        	[
	            'id' => 1,
	            'symbol' => 'english.png',
	            'code' => 'en_us',
	            'name' => 'US English',
	            'entity_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
	            'id' => 2,
	            'symbol' => 'english.png',
	            'code' => 'en_ind',
	            'name' => 'IND English',
	            'entity_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
	        	'id' => 3,
	            'symbol' => 'hindi.png',
	            'code' => 'hi_ind',
	            'name' => 'IND Hindi',
	            'entity_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ]
        ]);
    }
}
