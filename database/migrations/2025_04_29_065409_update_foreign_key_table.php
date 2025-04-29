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
        Schema::table('tickets', function(Blueprint $table) {
            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaign')->onDelete('cascade');
        });

        Schema::table('grand_price_winners', function(Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaign')->onDelete('cascade');
        });

        Schema::table('tickets_sold', function(Blueprint $table) {
            $table->string('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('user_tickets_bought_count', function(Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaign')->onDelete('cascade');
        });

        Schema::table('prices_category', function(Blueprint $table) {
            $table->string('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaign')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
