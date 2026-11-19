<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            Admin::factory()->create();
        }
    }
}
