<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->text('body');
			$table->tinyInteger('type');
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->unsignedBigInteger('user_id');
			$table->morphs('scopeable');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('events');
	}
}