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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name');
            $table->date('expiry_date')->nullable();
            $table->date('ssl_expiry_date')->nullable();
            $table->string('registrar_name')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->json('name_servers')->nullable();
            $table->text('domain_status')->nullable();
            $table->text('additional_info')->nullable(); // untuk data lainnya jika diperlukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
