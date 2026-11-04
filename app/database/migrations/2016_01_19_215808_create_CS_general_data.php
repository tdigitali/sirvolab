<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCSGeneralData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CS_general_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('last_mother_name')->nullable();
			$table->date('birthdate')->nullable();
			$table->integer('gender_id')->nullable()->unsigned();
			$table->integer('civil_status_id')->nullable()->unsigned();
			$table->integer('nationality_id')->nullable()->unsigned();
			$table->string('spouse')->nullable();
			$table->string('street')->nullable();
			$table->string('suburb')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->integer('country_id')->nullable()->unsigned();
			$table->string('postal_code')->nullable();
			$table->string('international_phone')->nullable();
			$table->string('office_phone')->nullable();
			$table->string('email')->nullable();
			$table->string('passport_number')->nullable();
			$table->integer('blood_type_id')->nullable()->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('gender_id')->references('id')->on('cat_genders')->onDelete('cascade');
			$table->foreign('civil_status_id')->references('id')->on('cat_civil_status')->onDelete('cascade');
			$table->foreign('nationality_id')->references('id')->on('cat_countries')->onDelete('cascade');
			$table->foreign('country_id')->references('id')->on('cat_countries')->onDelete('cascade');
			$table->foreign('blood_type_id')->references('id')->on('cat_blood_types')->onDelete('cascade');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('CS_general_data');
	}

}
