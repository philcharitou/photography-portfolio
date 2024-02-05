<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLineitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_line_items', function (Blueprint $table) {
            $table->increments('id');

            // Identification Field(s)
            $table->string('sku')->nullable();
            $table->string('description');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('tax')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('shipping')->nullable();
            $table->integer('handling')->nullable();
            $table->integer('fee')->nullable();
            $table->string('category')->nullable();
            $table->string('vendor')->nullable();
            $table->string('measurement')->nullable();
            $table->string('notes')->nullable();
            $table->date('date')->nullable();
            $table->integer('total');

            // Unsigned Integers
            $table->integer('invoice_id')->nullable()->unsigned();

            // Foreign Keys
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');

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
        Schema::dropIfExists('invoice_line_items');
    }
}
