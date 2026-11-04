<?php

class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->insert([
			[
				'name'             => 'Juan Manuel',
				'last_name'        => 'Rojo',
				'last_mother_name' => 'Márquez',
				'email'            => 'jrojo@sre.gob.mx',
				'password'         => Hash::make('123456'),
				'birthdate'       => '25/10/1989',
				'gender_id'          => 1,
				'blood_type_id'             => 1,
				'origin_country_id'             => 1,
				'residence_country_id'             => 1,
				'profile_id'             => 1,
				'created_at'       => date("Y-m-d H:i:s"),
				'updated_at'       => date("Y-m-d H:i:s")
			]
			// [
			// 	'user'             => 'marcosp',
			// 	'name'             => 'Marcos',
			// 	'last_name'        => 'Piñeiro',
			// 	'mother_last_name' => 'Velazquez',
			// 	'email'            => 'marcosp@sre.gob.mx',
			// 	'password'         => Hash::make('th2r5vxNkdcUFRWZ8mKBqQ9yJ'),
			// 	'profile_id'       => 2,
			// 	'acronym'          => 'MP00',
			// 	'ldap'             => 1,
			// 	'created_at'       => date("Y-m-d H:i:s"),
			// 	'updated_at'       => date("Y-m-d H:i:s")
			// ]
		]);
	}

}