<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceidInPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
          $table->unsignedInteger('invoice_id')->nullable()->after('factura_id');
          $table->foreign('invoice_id')->references('id')->on('suscripciones_invoices')->onDelete('cascade');

          $table->unsignedInteger('factura_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
          $table->dropForeign(['invoice_id']);
          $table->dropColumn('invoice_id');
          $table->unsignedInteger('factura_id')->nullable(false)->change();
        });
    }
}
