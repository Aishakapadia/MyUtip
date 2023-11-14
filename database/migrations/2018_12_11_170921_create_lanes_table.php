<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lanes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id_from');
            $table->integer('site_id_to');
            $table->string('title');
            //$table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->tinyInteger('active')->default(true)->comment('Status field');
            $table->integer('sort')->default(false)->comment('Set custom ORDER-BY');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lane_transporter', function (Blueprint $table) {
            $table->integer('lane_id');
            $table->integer('transporter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lanes');
        Schema::dropIfExists('lane_transporter');
    }
}
