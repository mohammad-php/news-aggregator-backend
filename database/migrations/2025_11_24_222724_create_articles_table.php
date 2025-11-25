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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(App\Models\Source::class)
                ->constrained('sources')
                ->onDelete('cascade');

            $table->foreignIdFor(App\Models\Category::class)
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('title', 1000);


            $table->text('description')->nullable();
            $table->text('content')->nullable();

            $table->string('author', 500)->nullable();
            $table->string('url', 1000);
            $table->string('image_url', 1000)->nullable();

            $table->timestamp('published_at')->index();

            $table->string('dedupe_hash')->unique();

            $table->timestamps();

            $table->index(['source_id', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
