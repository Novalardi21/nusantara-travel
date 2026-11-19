<?php

namespace Database\Seeders;

use App\Models\OperatorLoket;
use Illuminate\Database\Seeder;

class OperatorLoketSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            OperatorLoket::factory()->create();
        }
    }
}
