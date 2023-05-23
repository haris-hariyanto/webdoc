<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'website' => [
                'name' => 'Laravel',
            ],
            'home' => [
                'title' => '[app_name] - Search Documents, Notes, Summary, and Exam Prep for Free',
                'main_heading' => '[app_name]',
                'brief_paragraph' => 'Access a comprehensive collection of documents and notes on our website, designed to enhance your productivity and knowledge',
                'popular_documents_subheading' => 'Top Documents',
                'popular_documents_paragraph' => '',
                'popular_documents_total' => 24,
                'new_documents_subheading' => 'New Documents',
                'new_documents_paragraph' => '',
                'new_documents_total' => 24,
            ],
            'popular-documents' => [
                'permalink' => 'top-documents',
                'title' => 'Explore Top Documents, Notes, Summaries, and Exam Preparation Materials',
                'main_heading' => 'Top Documents, Notes, Summaries, and Exam Preparation Materials',
                'brief_paragraph' => '',
                'total_documents' => 48,
            ],
            'new-documents' => [
                'permalink' => 'new-documents',
                'title' => 'Browse New PDF Documents for Free',
                'main_heading' => 'Browse New PDF Documents for Free',
                'brief_paragraph' => '',
                'total_documents' => 48,
            ],
            'document' => [
                'permalink' => 'document',
            ],
            'search' => [
                'permalink' => 'search',
                'title' => 'Search Results : [keyword]',
                'main_heading' => 'Search Results : [keyword]',
                'total_documents' => 48,
            ],
            'proxy' => [
                'use_proxy' => 'N',
            ],
        ];

        foreach ($settings as $settingType => $settingItems) {
            foreach ($settingItems as $settingKey => $settingValue) {
                Setting::updateOrCreate(
                    [
                        'type' => $settingType,
                        'key' => $settingKey,
                        'website' => config('app.code'),
                    ],
                    [
                        'value' => $settingValue,
                    ]
                );
            }
        }
    }
}
