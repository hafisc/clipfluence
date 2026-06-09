<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kyc_status')->default('pending')->after('role');
            $table->string('kyc_document_type')->nullable()->after('kyc_status');
            $table->timestamp('kyc_reviewed_at')->nullable()->after('kyc_document_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kyc_status', 'kyc_document_type', 'kyc_reviewed_at']);
        });
    }
};
