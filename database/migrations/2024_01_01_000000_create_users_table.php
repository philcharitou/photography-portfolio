<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            // Statamic
            $table->boolean('super')->default(false);
            // Login Details
            $table->string('email')->unique();
            $table->string('password');
            // Identification Field(s)
            $table->string('id_number')->unique();
            $table->string('company')->nullable();
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
            $table->string('type')->nullable(); // user, admin, customer, supplier, etc.
            // Administrative Columns
            $table->string('notification_preference')->default('mail, database');
            $table->timestamp('last_pass_reset')->useCurrent();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_access')->nullable();
            // Geolocation
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            // Last Resource Feature
            $table->string('last_resource')->nullable();
            $table->string('last_resource_route')->nullable();
            // Authentication
            $table->dateTime('two_factor_renew')->useCurrent();
            // Boolean(s)
            $table->boolean('blocked')->default(false);
            $table->boolean('pass_reset')->default(true);
            $table->boolean('is_verified')->default(false);
            // Personal Detail(s)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
