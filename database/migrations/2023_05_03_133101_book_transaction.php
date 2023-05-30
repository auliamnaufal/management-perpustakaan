<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookTransaction extends Migration
{
  public function up(): void
  {
    Schema::create('book_transaction', function (Blueprint $table) {
      $table->id();

      $table->foreignId('book_id')->constrained()->cascadeOnDelete();
      $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('book_transaction');
  }
}
