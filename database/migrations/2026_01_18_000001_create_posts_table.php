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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            
            // Content fields
            $table->longText('html')->nullable();
            $table->longText('plaintext')->nullable();
            $table->longText('mobiledoc')->nullable();
            $table->longText('lexical')->nullable();
            
            // Media
            $table->string('feature_image')->nullable();
            $table->text('feature_image_alt')->nullable();
            $table->string('feature_image_caption')->nullable();
            
            // Post settings
            $table->boolean('featured')->default(false);
            $table->boolean('is_page')->default(false);
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->enum('visibility', ['public', 'members', 'paid'])->default('public');
            
            // Post type for LMS (course, lesson, blog, news, showcase)
            $table->string('post_type')->default('blog')->index();
            
            // Course-lesson relationship (for lessons only)
            $table->foreignId('course_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->integer('lesson_order')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('custom_excerpt')->nullable();
            
            // Open Graph
            $table->string('og_image')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            
            // Twitter Card
            $table->string('twitter_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['post_type', 'status', 'published_at']);
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
