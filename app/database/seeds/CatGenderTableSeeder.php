<?php

class CatGenderTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cat_genders')->insert([
			['name' => 'Hombre'],
			['name' => 'Mujer']
		]);
	}

}