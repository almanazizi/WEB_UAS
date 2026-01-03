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
        Schema::create('guest_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20);
            $table->string('nama', 100);
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->text('purpose');
            $table->timestamp('check_in_time');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('check_in_time');
            $table->index('lab_id');
            $table->index('nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_visitors');
    }
};
