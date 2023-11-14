<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent')->default(0);
            $table->string('title')->nullable();
            $table->string('page_slug')
                ->unique()
                ->nullable()
                ->comments('if page_slug is available, open a page, if url is available then skip it and open that url, and if page_slug and url is empty then it is non clickable link.');
            $table->string('url')
                ->nullable()
                ->comments('if page_slug is available, open a page, if url is available then skip it and open that url, and if page_slug and url is empty then it is non clickable link.');
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('ready')->default(0)->comment('if 1 show in the lists section.');
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_location_list', function (Blueprint $table) {
            $table->increments('id');
            // TODO:: in future make it functional
            //$table->integer('parent')->default(0);
            //$table->integer('sort')->default(0);

            $table->integer('menu_location_id')->unsigned()->index();
            $table->foreign('menu_location_id')->references('id')->on('menu_locations')->onDelete('cascade');

            $table->integer('menu_list_id')->unsigned()->index();
            $table->foreign('menu_list_id')->references('id')->on('menu_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_lists');
        Schema::dropIfExists('menu_location_list');
    }
}
