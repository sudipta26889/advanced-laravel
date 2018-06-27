<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_roles')->insert([
        	[
	            'role_id' => 1,
	            'model_type' => 'App\Models\User',
	            'model_id' => 1
	        ],
	        [
	        	'role_id' => 1,
	            'model_type' => 'App\Models\User',
	            'model_id' => 2
	        ]
        ]);
    }
}
