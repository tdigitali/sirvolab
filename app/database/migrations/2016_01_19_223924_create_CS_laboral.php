<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCSLaboral extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CS_laboral', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_id')->unsigned();
			$table->string('lb_actual_institution')->nullable();
			$table->date('lb_actual_start')->nullable();
			$table->text('lb_actual_charge')->nullable();
			$table->string('lb_previous_institution')->nullable();
			$table->date('lb_previous_start')->nullable();
			$table->text('lb_previous_charge')->nullable();

			$table->timestamps();
			$table->softDeletes();

			$table->foreign('general_id')->references('id')->on('CS_general_data')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('CS_laboral');
	}

}
