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
        Schema::create('khedmots', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('slug');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->bigInteger('program_id')->unsigned()->nullable();
            $table->string('other_program_name')->nullable();
            $table->decimal('khedmot_amount', 10, 2)->default(0);
            $table->decimal('manat_amount', 10, 2)->default(0);
            $table->decimal('kalyan_amount',10,2)->default(0);
            $table->decimal('rent_amount',10,2)->default(0);
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_collected')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khedmots');
        // Schema::dropForeign(['member_id']);
        // Schema::dropForeign(['user_id']);
    }
};
