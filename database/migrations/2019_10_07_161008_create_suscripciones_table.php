<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuscripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('planes')->onDelete('cascade');
            $table->unsignedInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->boolean('status')->nullable();
            $table->string('subscriptionId', 50)->comment('Flow');
            $table->timestamp('subscription_start')->nullable()->comment('Flow');
            $table->timestamp('period_start')->nullable()->comment('Flow');
            $table->timestamp('period_end')->nullable()->comment('Flow');
            $table->integer('status_flow')->comment('Flow');
            $table->timestamp('cancel_at')->nullable()->comment('Flow');
            $table->tinyInteger('cancel_type')->nullable()->comment('Flow');
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
        Schema::dropIfExists('suscripciones');
    }
}
