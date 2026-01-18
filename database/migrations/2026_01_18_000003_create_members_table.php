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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('password');
            
            // Profile
            $table->text('note')->nullable();
            $table->string('avatar')->nullable();
            
            // Status
            $table->enum('status', ['free', 'paid', 'comped'])->default('free');
            $table->timestamp('email_verified_at')->nullable();
            
            // Newsletter subscriptions
            $table->boolean('subscribed')->default(true);
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Sender
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_reply_to')->nullable();
            
            // Settings
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->boolean('subscribe_on_signup')->default(true);
            
            $table->timestamps();
        });

        Schema::create('member_newsletter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('newsletter_id')->constrained()->onDelete('cascade');
            $table->timestamp('subscribed_at')->useCurrent();
            
            $table->unique(['member_id', 'newsletter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_newsletter');
        Schema::dropIfExists('newsletters');
        Schema::dropIfExists('members');
    }
};
