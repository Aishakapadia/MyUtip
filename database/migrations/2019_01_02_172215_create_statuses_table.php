<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('visible');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('active')->default(true)->comment('Status field');
            $table->integer('sort')->default(false)->comment('Set custom ORDER-BY');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('status_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('status_ticket');
    }
}
