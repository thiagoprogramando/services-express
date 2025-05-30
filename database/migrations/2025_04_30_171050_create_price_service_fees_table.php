<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('price_service_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->constrained('prices')->onDelete('cascade');
            $table->foreignId('price_service_id')->constrained('price_services')->onDelete('cascade');
            $table->foreignId('fee_id')->constrained('service_fees')->onDelete('cascade');
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('price_service_fees');
    }
};
