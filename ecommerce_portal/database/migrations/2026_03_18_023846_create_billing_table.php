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
        Schema::create('billing', function (Blueprint $table) {
            $table->id('bill_id');
            $table->unsignedBigInteger('user_id');
            $table->string('country')->nullable();
            $table->string('fullname');
            $table->string('email');
            $table->string('pincode');
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing');
    }
};
