<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('price_id')->constrained('prices')->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('templates')->onDelete('cascade');
            $table->text('header')->nullable();
            $table->text('footer')->nullable();
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
