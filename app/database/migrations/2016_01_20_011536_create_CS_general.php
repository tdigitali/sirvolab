<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCSGeneral extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CS_general', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_id')->unsigned();
			$table->text('ge_activities')->nullable();
			$table->text('ge_publish')->nullable();
			$table->text('ge_awards')->nullable();
			$table->text('ge_scholarships')->nullable();
			$table->string('ge_notify')->nullable();
			$table->string('ge_relationship')->nullable();
			$table->integer('ge_country_id')->nullable()->unsigned();
			$table->string('ge_phone')->nullable();
			$table->string('ge_email')->nullable();
			$table->string('ge_fax')->nullable();
			$table->text('ge_address')->nullable();

			$table->timestamps();
			$table->softDeletes();

			$table->foreign('general_id')->references('id')->on('CS_general_data')->onDelete('cascade');
			$table->foreign('ge_country_id')->references('id')->on('cat_countries')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('CS_general');
	}

}
