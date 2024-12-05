<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserHistory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'superadmin1@gmail.com',
            'password' => '12345678'
        ]);
        UserHistory::factory()->create([
            'user_id' => 1,
            'input_text' => 'This is a sample input text for sentiment analysis.',
            'file_path' => 'uploads/text.txt',
            'negative_score' => 0.1,
            'neutral_score' => 0.6,
            'positive_score' => 0.3,
            'result' => 'Neutral',
            'created_at' => now()->subDays(31),
            'updated_at' => now()->subDays(31),
        ]);
    }
}
