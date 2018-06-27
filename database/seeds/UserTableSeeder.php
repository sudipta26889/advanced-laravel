<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	[
	            'name' => 'Admin',
	            'email' => 'admin@gmail.com',
	            'phone' => '8888888888',
	            'phone_country_code' => '91',
	            'password' => bcrypt('admin#123'),
	            'tnc_accepted' => true,
	            'entity_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ],
	        [
	        	'name' => 'Test Admin',
	            'email' => 'test.admin@gmail.com',
	            'phone' => '9999999999',
	            'phone_country_code' => '91',
	            'password' => bcrypt('admin#123'),
	            'tnc_accepted' => true,
	            'entity_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ]
        ]);
    }
}