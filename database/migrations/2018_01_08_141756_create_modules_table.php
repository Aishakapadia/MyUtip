<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_type_id')->unsigned();
            $table->integer('parent')->unsigned();
            //$table->foreign('parent')->references('id')->on('modules');
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->string('url');
            $table->tinyInteger('type')->default(0)->comment('0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download');
            $table->string('icon');
            $table->tinyInteger('visible_in_sidebar')->default(true)->comment('Visible in the left sidebar?');
            $table->tinyInteger('active')->default(true);
            $table->integer('sort')->default(0);
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
        Schema::dropIfExists('modules');
    }
}
