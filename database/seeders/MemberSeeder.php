<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'testing')) {
            Member::factory()->create();
        }
    }
}
