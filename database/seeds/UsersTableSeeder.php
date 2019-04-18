<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Let's clear the users table first
		User::truncate();
		
		$faker = \Faker\Factory::create();
		
		// Let's make sure everyone has the same password and
		// let's hash it before the loop, or else our seeder
		// will be too slow.
		$password = Hash::make('sitecrafting');
		
		User::create([
			'name' => 'Administrator',
			'store_pin' => '1234',
			'password' => $password,
		]);
		
		// And now let's generate a few dozen users for our app:
		for ($i = 0; $i < 1; $i++) {
			User::create([
				'name' => $faker->name,
				'store_pin' => $faker->email,
				'password' => $password,
				'role' => 1,
			]);
		}
	}
}