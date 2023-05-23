<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()
            ->create([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
            ]);
        
        \App\Models\Disk::create([
            'name' => 'Backblaze 1',
            'access_key' => '005dc4b826608830000000001',
            'secret_key' => 'K005K52CPpw6D7ZrBihYvIJsFR/C738',
            'bucket' => 'academiascribd',
            'endpoint' => 'https://s3.us-east-005.backblazeb2.com',
            'is_active' => 'Y',
        ]);
        
        $this->call([
            SettingsSeeder::class,
            // PageSeeder::class,
            MetaDataSeeder::class,
        ]);
    }
}
