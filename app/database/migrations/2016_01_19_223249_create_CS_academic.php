<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCSAcademic extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CS_academic', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_id')->unsigned();
			$table->string('aca_degree')->nullable();
			$table->string('aca_degree_average')->nullable();
			$table->string('aca_degree_institution')->nullable();
			$table->string('aca_degree_start_end')->nullable();
			$table->string('aca_degree_city')->nullable();
			$table->string('aca_mastery')->nullable();
			$table->string('aca_mastery_average')->nullable();
			$table->string('aca_mastery_institution')->nullable();
			$table->string('aca_mastery_start_end')->nullable();
			$table->string('aca_mastery_city')->nullable();
			$table->string('aca_doctorate')->nullable();
			$table->string('aca_doctorate_average')->nullable();
			$table->string('aca_doctorate_institution')->nullable();
			$table->string('aca_doctorate_start_end')->nullable();
			$table->string('aca_doctorate_city')->nullable();
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
		Schema::drop('CS_academic');
	}

}
