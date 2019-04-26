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
			'role' =>1
		]);
		
		User::create([
			'name' => 'employee',
			'store_pin' => '1111',
			'password' => $password,
			'role' =>2,
			'location'=>1
		]);
		
		User::create([
			'name' => 'employee',
			'store_pin' => '1112',
			'password' => $password,
			'role' =>2,
			'location'=>2
		]);
		
		User::create([
			'name' => 'employee',
			'store_pin' => '1113',
			'password' => $password,
			'role' =>2,
			'location'=>3
		]);
		
		User::create([
			'name' => 'employee',
			'store_pin' => '1114',
			'password' => $password,
			'role' =>2,
			'location'=>4
		]);
		
		User::create([
			'name' => 'employee',
			'store_pin' => '1115',
			'password' => $password,
			'role' =>2,
			'location'=>5
		]);
		
		User::create([
			'name' => 'employee',
			'store_pin' => '11110',
			'password' => $password,
			'role' =>2,
			'location'=>10
		]);
		

	}
}