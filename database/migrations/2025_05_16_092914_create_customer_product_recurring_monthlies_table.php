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
        Schema::create('customer_product_recurring_monthlies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_product_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_product_recurring_monthlies');
    }
};
