<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notification_channels')->insert([
            [
                'name' => 'Email',
                'key' => 'email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SMS',
                'key' => 'sms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
