<?php

use Illuminate\Database\Seeder;
use App\StoreLocation;

class StoreLocationTableSeeder extends Seeder
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
		StoreLocation::truncate();
		
		$faker = \Faker\Factory::create();
		
		// And now, let's create a few articles in our database:
		
		$storeLocations = [
			[
				'id' => 1,
				'name' => 'TE #1',
				'email' => '',
				'mobile' => '',
				'manager' => 'manager'
			],
			[
				'id' => 2,
				'name' => 'TE #2',
				'email' => '',
				'mobile' => '',
				'manager' => 'manager'
			],
			[
				'id' => 3,
				'name' => 'TE #3',
				'email' => '',
				'mobile' => '',
				'manager' => 'manager'
			],
			[
				'id' => 4,
				'name' => 'TE #4',
				'email' => '',
				'mobile' => '',
				'manager' => 'manager'
			],
			[
				'id' => 5,
				'name' => 'TE #5',
				'email' => '',
				'mobile' => '',
				'manager' => 'manager'
			],
			[
				'id' => 10,
				'name' => 'TM #10',
				'email' => 'jackyloop@outlook.com',
				'mobile' => '+8618250863109',
				'manager' => 'manager'
			],
		
		];
		
		
		foreach($storeLocations as $storeLocation) {
			StoreLocation::create([
				'id' => $storeLocation['id'],
				'name' => $storeLocation['name'],
				'email' => $storeLocation['email'],
				'mobile' => $storeLocation['mobile'],
				'manager' => $storeLocation['manager'],
			]);
		}
		
		
	}
}
