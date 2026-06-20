<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');

            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone');
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state');
            $table->string('delivery_zip');

            $table->foreignId('shipping_method_id')->constrained()->cascadeOnDelete();
            $table->dateTime('estimated_delivery')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
