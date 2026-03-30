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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('password');
            $table->text('address');
            $table->string('id_number');
            $table->string('business_name');
            $table->string('business_type');
            $table->string('gst_number');
            $table->string('business_category');
            $table->string('bank_account_no');
            $table->string('payment_method');
            $table->string('image');
            $table->string('status')->default('unverified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
