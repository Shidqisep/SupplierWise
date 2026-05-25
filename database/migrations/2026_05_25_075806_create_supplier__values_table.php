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
        Schema::create('supplier__values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_supplier')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('id_criteria')->constrained('criterias')->cascadeOnDelete();
            $table->float('score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier__values');
    }
};
