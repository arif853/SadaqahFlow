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
        Schema::create('receives', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->bigInteger('program_id')->unsigned()->nullable();
            $table->string('members')->nullable();
            $table->string('other_program_name')->nullable();
            $table->decimal('khedmot_amount',10,2)->default(0);
            $table->decimal('manat_amount',10,2)->default(0);
            $table->decimal('kollan_amount',10,2)->default(0);
            $table->decimal('rent_amount',10,2)->default(0);
            $table->decimal('total_amount',10,2)->default(0);
            $table->enum('status',['pending','collected','canceled'])->default('pending');
            $table->string('comment')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('collected_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('canceled_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receives');
    }
};
