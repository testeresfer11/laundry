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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->unsignedBigInteger('user_id');
            $table->float('total_amount');
            $table->longText('pickup_address')->nullable();
            $table->longText('delivery_address')->nullable();
            $table->enum('order_type',['store','online'])->default('online');
            $table->unsignedBigInteger('pickup_driver_id')->nullable();
            $table->unsignedBigInteger('delivery_driver_id')->nullable();
            $table->string('delivery_time')->nullable();
            $table->date('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
