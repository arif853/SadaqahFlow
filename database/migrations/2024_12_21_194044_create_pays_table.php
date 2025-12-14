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
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paid_by')->constrained('users');
            $table->timestamp('paid_at')->nullable();
            $table->date('date');
            $table->string('pay_to');
            $table->decimal('khedmot_amount',10,2)->nullable();
            $table->decimal('manat_amount',10,2)->nullable();
            $table->decimal('kalyan_amount',10,2)->nullable();
            $table->decimal('rent_amount',10,2)->nullable();
            $table->decimal('total_paid',10,2);
            $table->decimal('left_amount',10,2);
            $table->string('comment')->nullable();
            $table->enum('payment_status',['pending','paid','cancelled'])->default('paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
