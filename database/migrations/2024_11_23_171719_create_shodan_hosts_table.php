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
        Schema::create('shodan_hosts', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->json('hostnames')->nullable();
            $table->json('domains')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('organization')->nullable();
            $table->string('isp')->nullable();
            $table->string('asn')->nullable();
            $table->json('ports')->nullable();
            $table->json('vulns')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shodan_hosts');
    }
};
