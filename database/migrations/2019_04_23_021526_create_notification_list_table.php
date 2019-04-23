<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_list', function (Blueprint $table) {
			$table->increments('id');
			$table->string('type');
			$table->string('feature');
			$table->integer('location');
			$table->string('from_status');
			$table->string('to_status');
			$table->text('description')->nullable();
			$table->string('address');
			
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
        Schema::table('notification_list', function (Blueprint $table) {
            //
        });
    }
}
