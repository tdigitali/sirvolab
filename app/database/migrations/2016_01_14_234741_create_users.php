<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email');
			$table->string('password');
			$table->string('name');
			$table->string('last_name');
			$table->string('last_mother_name');
			$table->date('birthdate');
			$table->integer('gender_id')->unsigned();
			$table->integer('blood_type_id')->unsigned();
			$table->integer('origin_country_id')->unsigned();
			$table->integer('residence_country_id')->unsigned();
			$table->integer('profile_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('gender_id')->references('id')->on('cat_genders')->onDelete('cascade');
			$table->foreign('blood_type_id')->references('id')->on('cat_blood_types')->onDelete('cascade');
			$table->foreign('origin_country_id')->references('id')->on('cat_countries')->onDelete('cascade');
			$table->foreign('residence_country_id')->references('id')->on('cat_countries')->onDelete('cascade');
			$table->foreign('profile_id')->references('id')->on('cat_profiles')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
