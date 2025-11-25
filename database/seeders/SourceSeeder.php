<?php

namespace Database\Seeders;

use App\Enums\SourceCode;
use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Source::upsert([
            [
                'name' => SourceCode::NEWSAPI->label(),
                'code' => SourceCode::NEWSAPI->value
            ],
            [
                'name' => SourceCode::GUARDIAN->label(),
                'code' => SourceCode::GUARDIAN->value
            ],
            [
                'name' => SourceCode::NYTIMES->label(),
                'code' => SourceCode::NYTIMES->value
            ],
        ], ['code']);
    }
}
