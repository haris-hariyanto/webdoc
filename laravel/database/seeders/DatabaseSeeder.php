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
            'access_key' => '004db05af61c4e40000000017',
            'secret_key' => 'K004ZzzI9uEAOhTh1eHrSrv/kN4H8iE',
            'bucket' => 'webdoc',
            'endpoint' => 'https://s3.us-west-004.backblazeb2.com',
            'is_active' => 'Y',
        ]);
        
        $this->call([
            SettingsSeeder::class,
            // PageSeeder::class,
            MetaDataSeeder::class,
        ]);
    }
}
