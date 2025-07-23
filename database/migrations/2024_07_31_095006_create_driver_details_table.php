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
        Schema::create('driver_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->longText('profile')->nullable();
            $table->string('phone_number')->nullable();
            $table->longText('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->enum('gender',['Female','Male','Other'])->nullable();
            $table->string('country_code')->nullable();
            $table->date('dob')->nullable();
            $table->string('country_short_code')->nullable();
            $table->string('license_number')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_details');
    }
};
