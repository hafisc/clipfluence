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
            $table->string('phone')->nullable()->after('email');
            $table->string('company_name')->nullable()->after('name');
            $table->string('industry')->nullable()->after('company_name');
            $table->string('website')->nullable()->after('industry');
            $table->text('bio')->nullable()->after('website');
            $table->string('instagram_url')->nullable()->after('bio');
            $table->string('tiktok_url')->nullable()->after('instagram_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'company_name',
                'industry',
                'website',
                'bio',
                'instagram_url',
                'tiktok_url'
            ]);
        });
    }
};
