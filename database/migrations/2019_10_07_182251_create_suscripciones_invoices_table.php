<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuscripcionesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suscripciones_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('suscripcion_id');
            $table->foreign('suscripcion_id')->references('id')->on('suscripciones')->onDelete('cascade');
            $table->integer('invoiceId')->nullable()->comment('Flow');
            $table->float('amount', 14, 2)->nullable()->comment('Flow');
            $table->tinyInteger('attemp_count')->nullable()->comment('Flow');
            $table->tinyInteger('attemped')->nullable()->comment('Flow');
            $table->timestamp('next_attemp_date')->nullable()->comment('Flow');
            $table->timestamp('due_date')->nullable()->comment('Flow');
            $table->tinyInteger('status')->nullable()->comment('Flow');
            $table->json('response')->nullable()->comment('Flow');
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
        Schema::dropIfExists('suscripciones_invoices');
    }
}
