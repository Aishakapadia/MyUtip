<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticket_number')->unique();
            $table->integer('user_id')->comment('Request initiated by');
            $table->integer('vehicle_type_id');
            $table->integer('site_id_from');
            $table->integer('site_id_to');
            $table->integer('site_id_drop_off')->nullable();
            $table->string('delivery_challan_number')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('draft')->default(false);
            $table->dateTime('vehicle_required_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ticket_transporter', function (Blueprint $table) {
            $table->integer('ticket_id')->unsigned();
            $table->integer('transporter_status_id')->unsigned();
            $table->integer('transporter_id')->unsigned();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_contact')->nullable();
            $table->dateTime('eta')->nullable();
            $table->tinyInteger('confirmed')->default(false);
        });

        Schema::create('ticket_drop_off_sites', function (Blueprint $table) {
            $table->integer('ticket_id')->unsigned();
            $table->integer('site_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_transporter');
        Schema::dropIfExists('ticket_drop_off_sites');
    }
}
