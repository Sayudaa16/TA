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
        Schema::create('c_v_e_s', function (Blueprint $table) {
            $table->id();
            $table->string('cve_id')->unique();
            $table->text('description');
            $table->timestamp('created_at_api');
            $table->timestamp('updated_at_api');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_v_e_s');
    }
};
