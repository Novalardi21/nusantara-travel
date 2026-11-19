<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['member', 'admin', 'operator_loket'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $password = $table->string('password', 255);
                    if (in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
                        $password->charset('latin1')->collation('latin1_swedish_ci');
                    }

                    $password->change();
                });
            }
        }

        if (Schema::hasTable('member')) {
            Schema::table('member', function (Blueprint $table): void {
                $table->unique('username');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member')) {
            Schema::table('member', function (Blueprint $table): void {
                $table->dropUnique(['username']);
            });
        }

        // Keep the wider columns so rolling back cannot truncate stored password hashes.
    }
};
