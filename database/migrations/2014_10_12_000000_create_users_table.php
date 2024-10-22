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
            $table->id();
            $table->string('nia')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number',20)->unique();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->boolean('status_user');
            $table->foreignId('jabatan_id')->constrained('jabatans');
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
