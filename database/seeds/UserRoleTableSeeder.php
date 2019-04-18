<?php

use Illuminate\Database\Seeder;
use App\UserRole;

class UserRoleTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//
		// Let's truncate our existing records to start from scratch.
		UserRole::truncate();
		
		$faker = \Faker\Factory::create();
		
		// And now, let's create a few articles in our database:
		
		$userRoles = [
			[
				'role' => 'admin',
			],
			[
				'role' => 'employee',
			]
		
		];
		
		
		foreach($userRoles as $userRole) {
			UserRole::create([
				'role' => $userRole['role'],
			]);
		}
		
		
	}
}
