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
        Schema::create('campaign', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->unique();
            $table->string('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['active', 'inactive']);
            $table->integer('total_tickets');
            $table->decimal('ticket_price', 10, 2);
            $table->decimal('minimum_winnings', 10, 2);
            $table->enum('availability', ['available', 'sold_out']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign');
    }
};
