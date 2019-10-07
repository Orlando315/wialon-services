<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('servicio_id')->nullable();
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->string('descripcion');
            $table->float('monto', 14, 2);
            $table->boolean('status')->nullable();
            $table->string('commerceOrder', 15)->unique();
            $table->string('flow_token')->nullable();
            $table->string('flow_order')->nullable();
            $table->string('flow_url')->nullable();
            $table->json('response')->nullable();
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
        Schema::dropIfExists('facturas');
    }
}
