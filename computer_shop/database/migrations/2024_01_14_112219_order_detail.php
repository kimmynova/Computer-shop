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
        Schema::create('order_detail', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->integer('sub_quantity');
            $table->decimal('sub_amount',10,2);
            $table->decimal('discount',10,2);
            $table->foreignId('pro_id')->constrained('products');
            $table->foreignId('order_id')->constrained('orders');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
