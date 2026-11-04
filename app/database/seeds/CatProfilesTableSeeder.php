<?php

class CatProfilesTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cat_profiles')->insert([
			['name' => 'Administrador'],
			['name' => 'Usuario']
		]);
	}

}