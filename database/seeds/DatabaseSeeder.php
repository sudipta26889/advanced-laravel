<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
	        EntityTableSeeder::class,
	        LanguageTableSeeder::class,
	        SettingsTableSeeder::class,
	        UserTableSeeder::class,
            RoleTableSeeder::class,
            UserRoleTableSeeder::class,
            OauthClientsTableSeeder::class,
	    ]);
    }
}
