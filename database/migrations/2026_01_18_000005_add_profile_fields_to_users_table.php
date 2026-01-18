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
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->unique()->after('email');
            $table->text('bio')->nullable()->after('password');
            $table->string('profile_image')->nullable()->after('bio');
            $table->string('cover_image')->nullable()->after('profile_image');
            
            // Contact info
            $table->string('website')->nullable()->after('cover_image');
            $table->string('location')->nullable()->after('website');
            $table->string('facebook')->nullable()->after('location');
            $table->string('twitter')->nullable()->after('facebook');
            
            // Role
            $table->enum('role', ['admin', 'editor', 'author', 'contributor'])->default('author')->after('twitter');
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'bio', 'profile_image', 'cover_image',
                'website', 'location', 'facebook', 'twitter',
                'role', 'meta_title', 'meta_description'
            ]);
        });
    }
};
