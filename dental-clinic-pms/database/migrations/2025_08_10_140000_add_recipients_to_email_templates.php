<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('recipient_type')->nullable()->after('type'); // manual | roles | null
            $table->text('recipient_emails')->nullable()->after('recipient_type'); // CSV of emails for manual
            $table->text('recipient_roles')->nullable()->after('recipient_emails'); // CSV of role names for roles
        });
    }

    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['recipient_type', 'recipient_emails', 'recipient_roles']);
        });
    }
};
