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
            $table->string('order_type');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('address')->nullable();
            $table->string('type')->nullable();
            $table->integer('percent')->nullable();
            $table->decimal('sub_total', 7, 2)->nullable();
            $table->decimal('discount_amount', 7, 2)->nullable();
            $table->integer('delivery_charge')->nullable();
            $table->decimal('total', 7, 2)->nullable();
            $table->string('status')->default('pending');
            $table->longText('notes')->nullable();
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
