<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out', 'adjustment']); // nhập, xuất, điều chỉnh
            $table->integer('qty');
            $table->decimal('unit_cost', 12, 2)->nullable(); // giá tại thời điểm giao dịch
            $table->string('reference_type')->nullable(); // purchase_order, repair_job, adjustment...
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_number')->nullable(); // mã phiếu: PO-001, RJ-001...
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
