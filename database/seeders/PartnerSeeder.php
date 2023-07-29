<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Attachment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schemas = [
            [
                'link' => null,
                'image' => [
                    'name' => 'beiken.jpeg',
                    'size' => 35000
                ]
            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'halliburton.svg',
                    'size' => 14000
                ],

            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'ppc.png',
                    'size' => 16000
                ]
            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'schlumberger.svg',
                    'size' => 7000
                ]
            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'specmechservice.png',
                    'size' => 98000
                ],

            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'weatherford.svg',
                    'size' => 16000
                ]
            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'denimex.svg',
                    'size' => 9000
                ]
            ],
            [
                'link' => null,
                'image' => [
                    'name' => 'parker-drilling.png',
                    'size' => 4000
                ]
            ],
        ];

        foreach ($schemas as $i => $schema) {
            $model = Partner::create([
                'link' => $schema['link'],
                'order' => $i
            ]);
            Attachment::create($schema['image'] + [
                'original_name' => $schema['image']['name'],
                'group' => '',
                'type' => 'image',
                'attachmentable_id' => $model->id,
                'attachmentable_type' => Partner::class
            ]);
        }
    }
}
