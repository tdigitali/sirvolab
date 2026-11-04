<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCSActivities extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CS_activities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_id')->unsigned();
			$table->string('ac_program_name')->nullable();
			$table->string('ac_institution')->nullable();
			$table->string('ac_level')->nullable();
			$table->date('ac_start_program')->nullable();
			$table->date('ac_end_program')->nullable();
			$table->date('ac_start')->nullable();
			$table->string('ac_cycle')->nullable();
			$table->string('ac_evaluation')->nullable();
			$table->string('ac_period')->nullable();
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
		Schema::drop('CS_activities');
	}

}
