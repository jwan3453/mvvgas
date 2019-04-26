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
			
			//location 1
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
			
			//location 2
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
				'feature' => 'Pump #8',
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
			
			//location 3
			[
				'location' => 3,
				'feature' => 'Pump #1',
			],
			[
				'location' => 3,
				'feature' => 'Pump #2',
			],
			[
				'location' => 3,
				'feature' => 'Pump #3',
			],
			[
				'location' => 3,
				'feature' => 'Pump #4',
			],
			[
				'location' => 3,
				'feature' => 'Pump #5',
			],
			[
				'location' => 3,
				'feature' => 'Pump #6',
			],
			[
				'location' => 3,
				'feature' => 'Pump #7',
			],
			[
				'location' => 3,
				'feature' => 'Pump #8',
			],
			[
				'location' => 3,
				'feature' => 'Pump #9',
			],
			[
				'location' => 3,
				'feature' => 'Pump #9D',
			],
			[
				'location' => 3,
				'feature' => 'Pump #10',
			],
			[
				'location' => 3,
				'feature' => 'Pump #10D',
			],
			[
				'location' => 3,
				'feature' => 'Pump #11',
			],
			[
				'location' => 3,
				'feature' => 'Pump #11D',
			],
			[
				'location' => 3,
				'feature' => 'Pump #12',
			],
			[
				'location' => 3,
				'feature' => 'Pump #12D',
			],
			[
				'location' => 3,
				'feature' => 'Car Wash',
			],
			[
				'location' => 3,
				'feature' => 'Store',
			],
			
			//location 4
			[
				'location' => 4,
				'feature' => 'Pump #1',
			],
			[
				'location' => 4,
				'feature' => 'Pump #2',
			],
			[
				'location' => 4,
				'feature' => 'Pump #3',
			],
			[
				'location' => 4,
				'feature' => 'Pump #4',
			],
			[
				'location' => 4,
				'feature' => 'Pump #5',
			],
			[
				'location' => 4,
				'feature' => 'Pump #5D',
			],
			[
				'location' => 4,
				'feature' => 'Pump #6',
			],
			[
				'location' => 4,
				'feature' => 'Pump #6D',
			],
			[
				'location' => 4,
				'feature' => 'Pump #7',
			],
			[
				'location' => 4,
				'feature' => 'Pump #7D',
			],
			[
				'location' => 4,
				'feature' => 'Pump #8',
			],
			[
				'location' => 4,
				'feature' => 'Pump #8D',
			],
			[
				'location' => 4,
				'feature' => 'Store',
			],
			
			//location 5
			[
				'location' => 5,
				'feature' => 'Pump #1',
			],
			[
				'location' => 5,
				'feature' => 'Pump #2',
			],
			[
				'location' => 5,
				'feature' => 'Pump #3',
			],
			[
				'location' => 5,
				'feature' => 'Pump #4',
			],
			[
				'location' => 5,
				'feature' => 'Pump #5',
			],
			[
				'location' => 5,
				'feature' => 'Pump #6',
			],
			[
				'location' => 5,
				'feature' => 'Pump #7',
			],
			[
				'location' => 5,
				'feature' => 'Pump #7D',
			],
			[
				'location' => 5,
				'feature' => 'Pump #8',
			],
			[
				'location' => 5,
				'feature' => 'Pump #8D',
			],
			[
				'location' => 5,
				'feature' => 'Pump #9',
			],
			[
				'location' => 5,
				'feature' => 'Pump #10',
			],
			[
				'location' => 5,
				'feature' => 'Store',
			],
			
			//location 6
			[
				'location' => 6,
				'feature' => 'Pump #1',
			],
			[
				'location' => 6,
				'feature' => 'Pump #1D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #2',
			],
			[
				'location' => 6,
				'feature' => 'Pump #2D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #3',
			],
			[
				'location' => 6,
				'feature' => 'Pump #3D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #4',
			],
			[
				'location' => 6,
				'feature' => 'Pump #4D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #5',
			],
			[
				'location' => 6,
				'feature' => 'Pump #6',
			],
			[
				'location' => 6,
				'feature' => 'Pump #7',
			],

			[
				'location' => 6,
				'feature' => 'Pump #8',
			],

			[
				'location' => 6,
				'feature' => 'Pump #9',
			],
			[
				'location' => 6,
				'feature' => 'Pump #10',
			],
			[
				'location' => 6,
				'feature' => 'Pump #11',
			],
			[
				'location' => 6,
				'feature' => 'Pump #12',
			],
			
			[
				'location' => 6,
				'feature' => 'Pump #13',
			],
			
			[
				'location' => 6,
				'feature' => 'Pump #14',
			],
			[
				'location' => 6,
				'feature' => 'Pump #15',
			],
			[
				'location' => 6,
				'feature' => 'Pump #16',
			],
			[
				'location' => 6,
				'feature' => 'Pump #17',
			],
			[
				'location' => 6,
				'feature' => 'Pump #18',
			],
			
			[
				'location' => 6,
				'feature' => 'Pump #19',
			],
			[
				'location' => 6,
				'feature' => 'Pump #20',
			],
			[
				'location' => 6,
				'feature' => 'Pump #21',
			],
			[
				'location' => 6,
				'feature' => 'Pump #21D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #22',
			],
			[
				'location' => 6,
				'feature' => 'Pump #22D',
			],			[
				'location' => 6,
				'feature' => 'Pump #23',
			],
			[
				'location' => 6,
				'feature' => 'Pump #23D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #24',
			],
			[
				'location' => 6,
				'feature' => 'Pump #24D',
			],
			[
				'location' => 6,
				'feature' => 'Pump #25',
			],
			[
				'location' => 6,
				'feature' => 'Pump #25D',
			],			[
				'location' => 6,
				'feature' => 'Pump #26',
			],
			[
				'location' => 6,
				'feature' => 'Pump #26D',
			],			[
				'location' => 6,
				'feature' => 'Pump #27',
			],
			[
				'location' => 6,
				'feature' => 'Pump #27D',
			],			[
				'location' => 6,
				'feature' => 'Pump #84',
			],
			[
				'location' => 6,
				'feature' => 'Pump #28D',
			],
			[
				'location' => 6,
				'feature' => 'Car Wash',
			],
			
			[
				'location' => 6,
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
