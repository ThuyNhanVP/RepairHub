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
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_job_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->string('warranty_code')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('duration_months');
            $table->enum('status', ['active', 'expired', 'void'])->default('active');
            $table->text('terms')->nullable();
            $table->timestamps();

            $table->index(['status', 'end_date']);
            $table->index('customer_id');
            $table->index('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
