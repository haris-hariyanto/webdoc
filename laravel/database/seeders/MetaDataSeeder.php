<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class MetaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $metaData = [
            ['home', 'meta_data', 'home'],
            ['popular-documents', 'meta_data', 'popular-documents'],
            ['new-documents', 'meta_data', 'new-documents'],
            ['document', 'meta_data', 'document'],
        ];

        foreach ($metaData as $metaDataItem) {
            Setting::updateOrCreate(
                [
                    'website' => config('app.code'),
                    'type' => $metaDataItem[0],
                    'key' => $metaDataItem[1],
                ],
                [
                    'value' => view('stubs.meta-data.' . $metaDataItem[2])->render(),
                ]
            );
        }
    }
}
