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
        Schema::create('leads_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leads_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('master_status_id')->constrained('master_status')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_status');
    }
};
