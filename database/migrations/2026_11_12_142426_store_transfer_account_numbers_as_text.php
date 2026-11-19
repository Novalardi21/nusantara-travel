<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('konfirmasi_pesan')) {
            Schema::table('konfirmasi_pesan', function (Blueprint $table): void {
                $table->string('no_rek', 30)->change();
            });
        }
    }

    public function down(): void
    {
        // Keep account numbers as text because converting back would lose leading zeros.
    }
};
