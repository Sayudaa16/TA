<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cyber_threat_tweets', function (Blueprint $table) {
            $table->id();
            $table->string('tweet_id')->unique();
            $table->string('author_id');
            $table->string('author_username')->nullable();
            $table->text('text');
            $table->integer('retweet_count')->default(0);
            $table->integer('reply_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('quote_count')->default(0);
            $table->integer('bookmark_count')->default(0);
            $table->integer('impression_count')->default(0);
            $table->string('category'); // Kategori hasil klasifikasi
            $table->timestamp('tweet_created_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cyber_threat_tweets');
    }
};
