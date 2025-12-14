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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickName')->nullable();
            $table->string('father_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('bloodType')->nullable();
            $table->string('kollan_id');
            $table->string('kollan_khedmot')->default('0');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
