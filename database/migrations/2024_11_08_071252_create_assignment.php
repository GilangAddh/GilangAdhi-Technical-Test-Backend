<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('salesperson_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
