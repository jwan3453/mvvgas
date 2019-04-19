<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(IssueItemsTableSeeder::class);
		 $this->call(StoreLocationTableSeeder::class);
		 $this->call(StoreFeatureSeeder::class);
		 $this->call(UserRoleTableSeeder::class);
		 $this->call(UsersTableSeeder::class);
    }
}
