<?php

class CatBloodTypeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cat_blood_types')->insert([
			['name' => 'O-'],
			['name' => 'O+'],
			['name' => 'A−'],
			['name' => 'A+'],
			['name' => 'B−'],
			['name' => 'B+'],
			['name' => 'AB−'],
			['name' => 'AB+']
		]);
	}

}