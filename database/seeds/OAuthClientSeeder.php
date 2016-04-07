<?php

use Illuminate\Database\Seeder;

class OAuthClientSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){

		DB::table('oauth_clients')->truncate();

		for ($i=0; $i < 10; $i++){

			DB::table('oauth_clients')->insert(
				[   'id' => "id$i",
					'secret' => "secret$i",
					'name' => "Test Client $i"
				]
			);
		}
	}
}
