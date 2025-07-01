<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreachTable extends Migration
{
    public function up()
    {
        Schema::create('breach', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // contoh: email
            $table->string('email');
            $table->string('status')->default('running'); // contoh: running
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('breach');
    }
}

