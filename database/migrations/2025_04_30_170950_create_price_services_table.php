<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('price_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->constrained('prices')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('price_services');
    }
};
