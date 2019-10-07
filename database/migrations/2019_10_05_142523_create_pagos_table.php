<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('factura_id');
            $table->foreign('factura_id')->references('id')->on('facturas')->onDelete('cascade');
            $table->string('token');
            $table->string('flow_order');
            $table->timestamp('request_date');
            $table->tinyInteger('status');
            $table->string('payer');
            $table->timestamp('payment_date')->nullable();
            $table->string('medio')->nullable();
            $table->float('amount', 12, 2)->nullable();
            $table->float('fee', 12, 2)->nullable();
            $table->float('taxes', 12, 2)->nullable();
            $table->float('balance', 12, 2)->nullable();
            $table->timestamp('transfer_date')->nullable();
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
        Schema::dropIfExists('pagos');
    }
}
