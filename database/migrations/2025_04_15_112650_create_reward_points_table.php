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
        Schema::create('reward_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('order_id');
            $table->date('order_date')->nullable();
            $table->string('received_points')->nullable();
            $table->date('received_date')->nullable();
            $table->string('expired_points')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('available_points')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_points');
    }
};
