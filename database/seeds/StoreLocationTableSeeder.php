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
				'name' => 'TM #1',
			],
			[
				'name' => 'TM #2',
			],
			[
				'name' => 'TM #3',
			],
			[
				'name' => 'TM #4',
			],
			[
				'name' => 'TM #5',
			],
			[
				'name' => 'TM #10',
				'email' => 'jackyloop@outlook.com',
				'mobile' => '+8618250863109'
			],
		
		];
		
		
		foreach($storeLocations as $storeLocation) {
			StoreLocation::create([
				'name' => $storeLocation['name'],
			]);
		}
		
		
	}
}
