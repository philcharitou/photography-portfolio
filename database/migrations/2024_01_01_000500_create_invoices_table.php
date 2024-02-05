<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');

            // Identification Field(s)
            $table->string('issued_by');
            $table->string('issued_to');
            $table->string('id_number')->unique();
            $table->string('type');
            // Invoice Dates
            $table->date('issued_date'); // date type uses YYY-MM-DD
            $table->date('due_date')->nullable(); // date type uses YYY-MM-DD
            $table->date('paid_date')->nullable(); // date type uses YYY-MM-DD
            // Optional Field(s)
            $table->longText('description')->nullable(); // printed description
            $table->longText('notes')->nullable(); // internal notes
            // Boolean(s)
            $table->boolean('is_draft')->default(true);
            // Financial(s)
            $table->string('currency')->nullable();
            $table->integer('subtotal')->nullable()->default(0);
            $table->integer('tax')->nullable()->default(0);
            $table->integer('deposit')->nullable()->default(0);
            $table->integer('fees')->nullable()->default(0);
            $table->integer('total')->nullable()->default(0);
            $table->integer('balance')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};