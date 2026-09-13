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
        Schema::create('receptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['received', 'diagnosing', 'quoted', 'waiting_approval', 'repairing', 'waiting_parts', 'completed', 'delivered', 'cancelled'])->default('received');
            $table->text('description')->nullable();
            $table->text('diagnosis')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('final_cost', 12, 2)->nullable();
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('estimated_completion_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptions');
    }
};
