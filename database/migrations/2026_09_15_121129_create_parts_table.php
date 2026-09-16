<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('part_categories')->nullOnDelete();
            $table->string('brand')->nullable();
            $table->string('unit')->default('cái'); // cái, bộ, m, kg...
            $table->decimal('cost_price', 12, 2)->default(0); // giá nhập
            $table->decimal('sale_price', 12, 2)->default(0); // giá bán
            $table->integer('stock_qty')->default(0); // tồn kho hiện tại
            $table->integer('min_stock_qty')->default(5); // cảnh báo khi dưới mức này
            $table->string('location')->nullable(); // vị trí kho: A-01, B-02...
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
