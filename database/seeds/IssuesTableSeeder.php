<?php

use Illuminate\Database\Seeder;
use App\Issue;

class IssuesTableSeeder extends Seeder
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
		Issue::truncate();
	
		$faker = \Faker\Factory::create();
	
		// And now, let's create a few articles in our database:
		for ($i = 0; $i < 10; $i++) {
			Issue::create([
				'feature' => $faker->sentence,
				'location' => 6,
				'description' => $faker->paragraph,
				'diagnosed_issue' => $faker->sentence,
				'reported_issue' => $faker->sentence,
				'date_closed' => $faker->dateTime(),
				'status' => 'reported'
			]);
		}
    }
}
