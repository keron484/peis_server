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
        Schema::create('user_tickets_bought_count', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('tickets_bought_count')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->decimal('total_winnings', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tickets_bought_count');
    }
};
