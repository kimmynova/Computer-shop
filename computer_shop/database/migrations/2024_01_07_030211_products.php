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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
           $table->string('uuid',60);
           $table->string('prod_code',30)->nullable();
           $table->string('name');
           $table->text('description');
           $table->string('image_url');
           $table->decimal('unit_price',10,2);
           $table->foreignId('cate_id')->constrained('categories');
           $table->foreignId('brands_id')->constrained('brands');
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
