<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel Security Audit Log.
 *
 * Tabel ini menyimpan log aktivitas keamanan aplikasi
 * termasuk login, logout, percobaan serangan, dll.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 50)->index(); // login_success, sql_injection_attempt, dll.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45); // Support IPv6
            $table->text('user_agent')->nullable();
            $table->text('request_url')->nullable();
            $table->string('request_method', 10)->nullable();
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_audit_logs');
    }
};
