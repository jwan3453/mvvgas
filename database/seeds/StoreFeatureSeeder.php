<?php

use Illuminate\Database\Seeder;
use App\StoreFeature;

class StoreFeatureSeeder extends Seeder
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
		StoreFeature::truncate();
		
		$faker = \Faker\Factory::create();
		
		// And now, let's create a few articles in our database:
		
		$storeFeatures = [
			[
				'location' => 1,
				'feature' => 'Pump #1',
			],
			[
				'location' => 1,
				'feature' => 'Pump #2',
			],
			[
				'location' => 1,
				'feature' => 'Pump #3',
			],
			[
				'location' => 1,
				'feature' => 'Pump #4',
			],
			[
				'location' => 1,
				'feature' => 'Pump #5',
			],
			[
				'location' => 1,
				'feature' => 'Pump #6',
			],
			[
				'location' => 1,
				'feature' => 'Pump #7',
			],
			[
				'location' => 1,
				'feature' => 'Pump #9',
			],
			[
				'location' => 1,
				'feature' => 'Pump #9',
			],
			[
				'location' => 1,
				'feature' => 'Pump #10',
			],
			[
				'location' => 1,
				'feature' => 'Pump #11',
			],
			[
				'location' => 1,
				'feature' => 'Pump #12',
			],
			[
				'location' => 1,
				'feature' => 'Store',
			],
			[
				'location' => 2,
				'feature' => 'Pump #1',
			],
			[
				'location' => 2,
				'feature' => 'Pump #2',
			],
			[
				'location' => 2,
				'feature' => 'Pump #3',
			],
			[
				'location' => 2,
				'feature' => 'Pump #4',
			],
			[
				'location' => 2,
				'feature' => 'Pump #5',
			],
			[
				'location' => 2,
				'feature' => 'Pump #6',
			],
			[
				'location' => 2,
				'feature' => 'Pump #7',
			],
			[
				'location' => 2,
				'feature' => 'Pump #9',
			],
			[
				'location' => 2,
				'feature' => 'Pump #9',
			],
			[
				'location' => 2,
				'feature' => 'Pump #10',
			],
			[
				'location' => 2,
				'feature' => 'Pump #11',
			],
			[
				'location' => 2,
				'feature' => 'Pump #12',
			],
			[
				'location' => 2,
				'feature' => 'Store',
			],
		];
		
		
		foreach($storeFeatures as $storeFeature) {
			StoreFeature::create([
				'location' => $storeFeature['location'],
				'feature' => $storeFeature['feature']
			]);
		}
		
		
	}
}
