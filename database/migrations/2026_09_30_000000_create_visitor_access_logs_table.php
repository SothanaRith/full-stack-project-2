<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_access_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('accessed_at', precision: 3)->index();
            $table->string('ip_address', 45)->index();
            $table->string('http_method', 10);
            $table->string('route_name')->nullable()->index();
            $table->text('url_path');
            $table->unsignedSmallInteger('response_status')->index();
            $table->string('referrer_domain')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser_name')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os_name')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device_category', 20)->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedInteger('duration_ms')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_access_logs');
    }
};
