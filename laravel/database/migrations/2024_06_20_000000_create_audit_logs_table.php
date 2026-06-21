<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('action'); // e.g., 'user_registered', 'user_login', 'order_created'
            $table->string('model_type')->nullable(); // e.g., 'User', 'Order', 'Cart'
            $table->unsignedBigInteger('model_id')->nullable(); // ID of affected model
            $table->text('description')->nullable(); // Human-readable description
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('changes')->nullable(); // Before/after changes
            $table->timestamps();

            // Indexes for common queries
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
