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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255);
            $table->string('email');
            $table->string('nisn', 20)->nullable();
            $table->enum('class', ['1', '2', '3'])->nullable();
            $table->dateTime('pickup_date');
            $table->dateTime('return_date');
            $table->dateTime('actual_return_date')->nullable()->default(null);
            $table->foreignId('book_id')->constrained();
            $table->boolean('is_returned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
