<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
        	[
	            'id' => env('OAUTH_CLIENT', 1),
	            'name' => 'Super Access Client',
	            'secret' => env('OAUTH_CLIENT_SECRET', "63uIIRMc9cnEbreQH9Vj0jFNHYy2QCKZke4vYre2"),
	            'redirect' => 'http://localhost:8000/api/callback',
	            'personal_access_client' => 1,
	            'password_client' => 1,
	            'revoked' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
	        ]
        ]);
        DB::table('oauth_personal_access_clients')->insert([
            [
                'id' => 1,
                'client_id' => env('OAUTH_CLIENT', 1),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
