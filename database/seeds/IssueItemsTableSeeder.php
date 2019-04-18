<?php

use Illuminate\Database\Seeder;
use App\IssueItem;

class IssueItemsTableSeeder extends Seeder
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
		IssueItem::truncate();
		
		$faker = \Faker\Factory::create();
		
		// And now, let's create a few articles in our database:
		
		$issueItems = [
			[
				'name' => 'Drive off',
				'type' => 'Pump',
			],
			[
				'name' => 'Leak',
				'type' => 'Pump',
			],
			[
				'name' => 'No Debit/Credit',
				'type' => 'Pump',
			],
			[
				'name' => 'Retractor',
				'type' => 'Pump',
			],
			[
				'name' => 'No Dispense',
				'type' => 'Pump',
			],
			[
				'name' => 'No Grade Select',
				'type' => 'Pump',
			],
			[
				'name' => 'Printer Issue',
				'type' => 'Pump',
			],
			[
				'name' => 'Receipt Door',
				'type' => 'Pump',
			],
			[
				'name' => 'Screen Issue',
				'type' => 'Pump',
			],
			[
				'name' => 'Nozzle/HoseDamage',
				'type' => 'Pump',
			],
			[
				'name' => 'Other',
				'type' => 'Pump',
			],
			[
				'name' => 'Top Brush Issue',
				'type' => 'Car Wash',
			],
			[
				'name' => 'PS Brush Issue',
				'type' => 'Car Wash',
			],
			[
				'name' => 'DS Brush Issue',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Pit Issue',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Chain Not Moving',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Reclaim System Issue',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Chemical/WaterLeak(Tunnel)',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Chemical/WaterLeak(Mech.)',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Alarm',
				'type' => 'Car Wash',
			],
			[
				'name' => 'Other',
				'type' => 'Car Wash',
			],
			[
				'name' => '“L”Alarm',
				'type' => 'Store',
			],
			[
				'name' => 'No Debit/Credit',
				'type' => 'Store',
			],
			[
				'name' => 'FrozenRegister',
				'type' => 'Store',
			],
			[
				'name' => 'Other',
				'type' => 'Store',
			],
			
		];
		
		
		foreach($issueItems as $issueItem) {
			IssueItem::create([
				'name' => $issueItem['name'],
				'type' => $issueItem['type'],
			]);
		}


	}
}
