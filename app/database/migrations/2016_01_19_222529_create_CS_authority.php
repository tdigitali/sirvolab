<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCSAuthority extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('CS_authority', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('general_id')->unsigned();
			$table->string('au_name')->nullable();
			$table->string('au_institution')->nullable();
			$table->string('au_charge')->nullable();
			$table->boolean('au_support')->nullable();
			$table->string('au_support_institution')->nullable();
			$table->string('au_period')->nullable();
			$table->string('au_type_support')->nullable();
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
		Schema::drop('CS_authority');
	}

}
