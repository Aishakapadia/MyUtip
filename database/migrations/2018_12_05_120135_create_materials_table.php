<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sap_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->char('type', 2)->comment('RM/PM');
            $table->tinyInteger('active')->default(true)->comment('Status field');
            $table->integer('sort')->default(false)->comment('Set custom ORDER-BY');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
