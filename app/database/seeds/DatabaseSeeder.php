<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('CatBloodTypeTableSeeder');
		$this->call('CatCountriesTableSeeder');
		$this->call('CatGenderTableSeeder');
		$this->call('CatProfilesTableSeeder');
		$this->call('UserTableSeeder');
	}

}
