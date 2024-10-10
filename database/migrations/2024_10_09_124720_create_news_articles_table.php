<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('source_name', 255)->nullable();
            $table->string('source_id', 255)->nullable();
            $table->string('author', 255)->nullable();
            $table->text('title');
            $table->text('abstract')->nullable();
            $table->longText('content');
            $table->text('url');
            $table->text('url_to_image')->nullable();
            $table->timestamp('published_at');
            $table->json('keywords')->nullable();
            $table->string('section_name', 255)->nullable();
            $table->string('news_type', 255)->nullable();
            $table->integer('word_count')->nullable();
            $table->string('document_type', 255)->nullable();
            $table->timestamps();
        });

        // Create indexes
        Schema::table('news_articles', function (Blueprint $table) {
            $table->index('source_name');
            $table->index('published_at');
            $table->index('section_name');
            $table->index('news_type');
        });

        // Add a unique index on the first 191 characters of the url
        DB::statement('CREATE UNIQUE INDEX unique_news_article_url ON news_articles (url(191))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
