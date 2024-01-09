<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Attachment;
use App\Enums\CategoryType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->seedEquipment();
        $this->seedService();
    }

    private function seedService()
    {
        $shemas =$this->getServicesSchema();

        foreach ($shemas as $shema) {
            $model = Category::create([
                'category_id' => null,
                'type' => CategoryType::SERVICE,
                'is_active' => true
            ]);
            $model->saveTranslations([
                'name' => ['en'=>$shema['name']['en']],
                'slug' => ['en'=>Str::slug($shema['name']['en'])]
            ]);
            $this->saveChildCat($model, $shema['childs']??[]);
        }
    }

    private function seedEquipment()
    {
        $shemas =$this->getSchema();

        foreach ($shemas as $shema) {
            $model = Category::create([
                'category_id' => null,
                'type' => CategoryType::EQUIPMENT,
                'is_active' => true
            ]);
            $model->saveTranslations([
                'name' => ['en'=>$shema['name']['en']],
                'slug' => ['en'=>Str::slug($shema['name']['en'])]
            ]);
            Attachment::create($shema['image'] + [
                'original_name' => $shema['image']['name'],
                'group' => '',
                'type' => 'image',
                'attachmentable_id' => $model->id,
                'attachmentable_type' => Category::class
            ]);
            $this->saveChildCat($model, $shema['childs']??[]);
        }
    }

    private function saveChildCat($parent, $childs)
    {
        if (!$childs) {
            return;
        }
        foreach ($childs as $child) {
            $model = Category::create([
                'category_id' => $parent->id,
                'type' => $parent->type, // 'Equipment' or 'Service
                'is_active' => true
            ]);
            // $model->saveTranslations([
            //     'name' => $child['name'],
            //     'slug' => makeSlugs($child['name'])
            // ]);
            $model->saveTranslations([
                'name' => ['en'=>$child['name']['en']],
                'slug' => ['en'=>Str::slug($child['name']['en'])]
            ]);
            $this->saveChildCat($model, $child['childs']??[]);
        }
    }

    private function makeSlugs($names)
    {
        $res = [];
        foreach ($names as $locale => $name) {
            $res[$locale] = Str::slug($name);
        }

        return $res;
    }

    private function getSchema()
    {
        return [
            // Rig & Accessories
            [
                'name' => [
                    'en' => 'Rig & Accessories',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'rigs.png',
                    'size' => '48000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Hook Block',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Swivel',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rotary Table',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Kelly Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Draw works',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic Power Unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Air Winch',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic Winch',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Generator Set & spare parts',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Bucking Unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Weight Indicator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'BOP Testing Unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Top Drive Drilling System',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Deadline Anchor',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drilling hose',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Wire rope / Wireline',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cementing unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mast',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Substructure',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mobile drilling rig',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cat walks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Monkey board',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Dog house',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rails',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rig up & down system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Well Control Equipment
            [
                'name' => [
                    'en' => 'Well Control Equipment',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'boe.png',
                    'size' => '28000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'IBOP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'BOP Control unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drill Pipe Float Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Annular BOP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Ram BOP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drilling Spool',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Accumulator Unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Choke Manifold / Kill Manifold',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Lubricator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'BOP Parts',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Flare system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Lines',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Valves & weels',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Solid Control Equipment
            [
                'name' => [
                    'en' => 'Solid Control Equipment',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'mud-circulation.png',
                    'size' => '47000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Shale Shaker',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Cleaner',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Desander',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Desilter',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Vacuum Degasser',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Decanter Centrifuge',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Gas Separator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sand Pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Agitator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Tank',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Gun',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Shaker Screen',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hydrocyclone',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hoses',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sludge pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Filters',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Spare parts',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Drill String
            [
                'name' => [
                    'en' => 'Drill String',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'jars.png',
                    'size' => '35000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Square Kelly / Hexagonal Kelly',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drill Pipe (DP)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Heavy Weight Drill Pipe(HWDP)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drill Collar (DC)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Stabilizer',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Blade stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Spiral-blade stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Preumopercussion stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Expanding stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Roller stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Cones & Rollers for stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'PDC stabiliser',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Centralizer',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Filters',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hole Opener',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drilling Motor',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Lifting Sub',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drill Subs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drifts',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drill Bit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Bicentric bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Bit breakers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Measuring devices (Diameter)',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Wing bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Bit nozzles',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Pneumopercussion bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Cone bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Carbide bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'PDC bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'TSP bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Down Hole Motors',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Rotational',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Percussion',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Pneumatic',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Electrical',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Hydraulic',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            // Handling Tools
            [
                'name' => [
                    'en' => 'Handling Tools',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'pipe-handling.png',
                    'size' => '72000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Drilling Elevator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Elevator Link',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Manual Tong',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Slip',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Safety Clamp',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Power Tong',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Spider',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Stabbing Guide',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pipe Wiper',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sucker Rod Tools',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Quick Release Thread Protector',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Lifting Cap',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing Bushing and Insert Bowls',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing Spider And Insert Bowl',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Roller Kelly Bushing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rotary table Bushing And Insert',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Bowls',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Dies',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Fill In Circulate Tools',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Iron Roughneck',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Spinning Wrenches',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Elevator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic tong',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic tong control unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Tubing tong',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing tong',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Forks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pipe spanner',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Chain spanner',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hinged tong',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Downhole Tools
            [
                'name' => [
                    'en' => 'Downhole Tools',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'dhm.png',
                    'size' => '17000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Casing Centralizer',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cementing Plug',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Float Collar & Float Shoe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'External Hook',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Internal Hook',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing Cup Tester',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Fishing tools',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Fishing Magnet',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Fishing Jar',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Drift',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Junk mills',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Junk Basket',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Die Collar',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Taper Tap',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Junk Sub',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Overshot',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Releasing Spear',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Stop Collar',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing Scraper',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Milling Shoes',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing Cutter',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Mud Pump & Spare Parts
            [
                'name' => [
                    'en' => 'Mud Pump & Spare Parts',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'pumps.png',
                    'size' => '50000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Mud Pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Centrifugal pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Plunger pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sinkig pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Pump Unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Pump Liner',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Pump Piston',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Pump Valve & Seat',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pulsation Dampener',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Fluid End Module',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic components',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Engine',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Gear box',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Control unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Filters',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Safety valves',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cooling system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Spare parts',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ]
                ]
            ],
            // Production Equipment & OCTG
            [
                'name' => [
                    'en' => 'Production Equipment & OCTG',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'cementing.png',
                    'size' => '22000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Pumping Unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sucker Rod',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sucker Rod Pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Progressive Cavity Pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Electric Submersible Pump',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sucker Rod Guide',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Casing Pipe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Tubing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Line Pipe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Fiber Reinforced Plastic Pipe (FRP Pipe)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Screen Pipe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Vacuum Insulated Tubing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pup Joint',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Thread Protector',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Thread Gauge',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Coupling',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Shoe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pipe joints',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Crossovers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Scratcher',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cementing tools',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Packers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Wellhead Equipment
            [
                'name' => [
                    'en' => 'Wellhead Equipment',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'well-head.png',
                    'size' => '45000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Casing Head',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Tubing Head',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Christmas Tree',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Blind Flange',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Companion Flange',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Double Studded Adapter (DSA)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Weld Neck Flange',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Tee & Cross',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud Gate Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Gate Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Check Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Crossover Adapter',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Spacer Spool',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Flowline Products
            [
                'name' => [
                    'en' => 'Flowline Products',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'parts.png',
                    'size' => '26000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Swivel Joint',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hose Loop',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Integral Fitting',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Integral Pup Joint',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Hammer Union',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Relief Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Check Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Plug Valve',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ]
                ]
            ],
            // Laboratory of mud chemical-analysis
            [
                'name' => [
                    'en' => 'Laboratory of mud chemical-analysis',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'chem-lab-eq.png',
                    'size' => '41000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Density measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'ASG measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Viscosity measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'SSS measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'pH measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Water loss measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Geo-physical borehole survey
            [
                'name' => [
                    'en' => 'Geo-physical borehole survey',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'logging.png',
                    'size' => '75000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Coring equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Coring boxes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Core BBL',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Core recievers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Coring pipes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Corint bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ]
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Well logging',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Video logging',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Additional equipment',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Continuous directional survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Caliper log',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Logging units',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Coils',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Winches',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Magnetic logging',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Water flow survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Radiometrics',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Flow survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Data recording system',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Seismic measurements',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Photometry & nephelometry',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Geoelectric survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sensors',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cables',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cameras',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Data recording system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Special loggin equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Others & Spare parts
            [
                'name' => [
                    'en' => 'Others & Spare parts',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'misc-eq.png',
                    'size' => '62000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Additional equipment & electrics',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Camp houses',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Lubricants',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pneumatic system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Power supply system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Fuel storage system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Chemical reagents',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Fire safety system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
        ];
    }

    private function getSchemaOld()
    {
        return [
            // Other
            [
                'name' => [
                    'en' => 'Other',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'other.svg',
                    'size' => '1179'
                ],
            ],
            // Parts
            [
                'name' => [
                    'en' => 'Parts',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'parts.png',
                    'size' => '26000'
                ],
            ],
            // Bits
            [
                'name' => [
                    'en' => 'Drilling bits',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'bits.png',
                    'size' => '37000'
                ],
                'chlids' => [
                    [
                        'name' => [
                            'en' => 'Bicentric bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Measuring devices (Diameter)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Wing bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Bit nozzles',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pneumopercussion bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cone bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Carbide bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'PDC bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'TSP bits',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Drilling Pipes
            [
                'name' => [
                    'en' => 'Drilling pipes',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'pipes.png',
                    'size' => '45000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'DP with slip-joints',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Lightweight DP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Float collars',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Pipe joints',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Crossovers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Dampe crossovers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Packer rubbers for pipe connection',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Stabilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Standart drilling pipes',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'HWDP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'DC',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Slick',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                            [
                                'name' => [
                                    'en' => 'Spiral',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Filters',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Centralizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drifts',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Rig
            [
                'name' => [
                    'en' => 'Drilling rig',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'rigs.png',
                    'size' => '48000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Stakedowns & span ropes',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Drillers house',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mast',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'MDU',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Cat walks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Substructure',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Monkey board',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Dog houses',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rails',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rig up & down systems',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ]
                    ],
                ]
            ],
            // Pump
            [
                'name' => [
                    'en' => 'Pumps for rig',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'pumps.png',
                    'size' => '50000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Injection pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic components',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Hydraulic actuators',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Hydraulic pulsers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Pressure controller',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Engine',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Float Collar',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Pump controll unit',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Supercharger pumps',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Pneumatic compensators',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Suction line filters',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Pressure line filters',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Safe valves',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Cooling system',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Component',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Positive displacement pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Centrifugal type pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Plunger pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Sinking pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Components',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Compensators',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Float Collars',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Filters',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                                'name' => [
                                    'en' => 'Barrels',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // Mud
            [
                'name' => [
                    'en' => 'Drilling mud & circulation',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'mud-circulation.png',
                    'size' => '47000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Buffer tank',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Dampers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Compressors',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hight pressure lines',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Manifolds',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Mud cleaning system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Shale shakers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Degassers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Desilters',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Desenders',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud recycling system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Mud treatment system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Mixers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Mud cones',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Mixing unit',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Mud storage',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Tanks',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Standpipe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Filters',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Trash pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hoses',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // boreholeSurvey
            [
                'name' => [
                    'en' => 'Geo-physical borehole survey',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'logging.png',
                    'size' => '75000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Coring equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Coring boxes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Core BBL',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Core recievers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Coring pipes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Corint bits',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Well logging',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Video logging',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Additional equipment',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Continuous directional survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Caliper log',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Logging units',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Coils',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Winches',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Magnetic logging',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Water flow survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Radiometrics',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Flow survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Data recording system',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Seismic measurements',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Photometry & nephelometry',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Geoelectric survey',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // miscHelpEq
            [
                'name' => [
                    'en' => 'Additional equipment and electrics',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'misc-eq.png',
                    'size' => '62000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Tool grinding machinery',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Manual tool grinding machinery',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Grinding caps',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Washing machines',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Wrenches',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Concrete pillars for lines holding',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cutters',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Welding',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Gas welding machine',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Electic welding machine',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // motor
            [
                'name' => [
                    'en' => 'DHM',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'dhm.png',
                    'size' => '17000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Rotational',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Percussion',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Pneumatic',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'PDM',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Electrical',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // control
            [
                'name' => [
                    'en' => 'Measuring equipment & parameter monitoring',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'measure-eq.png',
                    'size' => '64000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Sensors',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Tools for measuring',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cables',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cameras',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Data recording system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Special logging equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // Stubs
            [
                'name' => [
                    'en' => 'Stubilizers',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'stabilizers.png',
                    'size' => '39000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Balde stubilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Pneumopercussion stubilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Expanding stubilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Pin&roller stubilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cones & rollers for stubilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'PDC stubilizers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // camp
            [
                'name' => [
                    'en' => 'Camp',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'camp.png',
                    'size' => '25000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Shower',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Houses',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Kitchen',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Doctor house',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'WC',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Electrics',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // casingCementing
            [
                'name' => [
                    'en' => 'Casing & cementing',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'cementing.png',
                    'size' => '22000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Casing pipes',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Casing hardware',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Boxes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Float Collars',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Shoe guide plugs',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Centralisers & turbulizers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Boxes',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Joints',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Crossovers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Scratcher',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cementing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Cementing baskets',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'DV colars',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Cementing equipment using stinger',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Equipment for cementing bridge',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Float Collars',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Packers',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Cementing plugs',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Cement baffle collar',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // emergency
            [
                'name' => [
                    'en' => 'Emergency responce',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'emergency.png',
                    'size' => '50000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Emergency valves',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Bath',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Fishing tools',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Overshots',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Magnetic',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Taper tap',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Sigils',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Junk mills',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Junk backets',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // lubricator
            [
                'name' => [
                    'en' => 'Lubricants',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'luricant.png',
                    'size' => '28000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'For DP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'For tongs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'For pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'For tubing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'For casing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // tubingEq
            [
                'name' => [
                    'en' => 'Tubing equipment',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'tubing.png',
                    'size' => '60000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Knock-off valves',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Coil tubing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Tubing',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Joints',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Crossovers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // wellHeadEq
            [
                'name' => [
                    'en' => 'Wellhead equipment',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'well-head.png',
                    'size' => '45000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Casing spool',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'X-mass tree',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // packer
            [
                'name' => [
                    'en' => 'Packer assembly',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'packers.png',
                    'size' => '13000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Pumps',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Float Collars',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Standart packer',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Pipes',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hoses',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // airUtility
            [
                'name' => [
                    'en' => 'Air utility',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'air-utility.png',
                    'size' => '6000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Air lines',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Air tanks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Compensator',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Air driers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // boe
            [
                'name' => [
                    'en' => 'Blow out prevention system',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'boe.png',
                    'size' => '28000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'BOP control unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Hydroacumulator',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Manual',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Remote control unit',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Degassers',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Valves & wheels',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Lines',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Kill',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Chock',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Manifilds',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Kill',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Chock',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rams',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Blind',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Cut',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Pipe',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'BOP',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Single',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Dounle',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Annular',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Drilling spool',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Flare system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Flare body',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Flare controll & automation system',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Flare remote starter',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Flare lines',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // rotory
            [
                'name' => [
                    'en' => 'Rotory system',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'rotary.png',
                    'size' => '62000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Kelly pipe',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Swivel',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'TDS',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Emergency system',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Shaft',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Hydraulic system',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Mud pipe',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Sensors',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Control station',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Components',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Ball valves',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'TDS rail',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Float Collars',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Wash pipe',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Control unit',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Bails',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Electical motors',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Rotor',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Kelly bushes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Components',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Rotor drive',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Brakes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // power
            [
                'name' => [
                    'en' => 'Power system',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'power.png',
                    'size' => '63000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Distribution unit',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Generators',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cables',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Power converters (MMC)',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // simCasing
            [
                'name' => [
                    'en' => 'Simultaneous casing system',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'sim-casing.png',
                    'size' => '26000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Symmetrical simultaneous casing system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Simultaneous casing system with extendable blades',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Expandable drilling bit',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Casing shoe',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // diselStorage
            [
                'name' => [
                    'en' => 'Disel storage',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'disel.png',
                    'size' => '32000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Bombs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Filling stations',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Tanks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Measuring equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Fuel',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // specMachinery
            [
                'name' => [
                    'en' => 'Special and heavy machinery',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'machinery.png',
                    'size' => '55000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Crane',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Forklifter',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Trucks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Cementing truck',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // lifting
            [
                'name' => [
                    'en' => 'Block and tackle system',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'lifting.png',
                    'size' => '83000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Emergency drive',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Drilling hook',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Drilling line system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Reel',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Drive',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Cooling System',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Brakes',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Drilling line',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Electical Engines',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Additional winches & drawworks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Dead-line anchorage',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Crown block',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Travel block',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // pipeHandling
            [
                'name' => [
                    'en' => 'Pipe handling tools and pipe locking',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'pipe-handling.png',
                    'size' => '72000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Slips',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Heaving plug',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Manual clamp',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Spiders',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Pipe clamp',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Casing grip',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Elevators',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Standart elevators',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                            [
                                'name' => [
                                    'en' => 'Elevators with inner pipe pick-up',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ],
                        ]
                    ],
                ]
            ],
            // hseEq
            [
                'name' => [
                    'en' => 'Equipment for HSE',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'ppe.png',
                    'size' => '33000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Fire hazard',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Signalization',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Life supply',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Light system',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'PPO',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Additional equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // tong
            [
                'name' => [
                    'en' => 'Drilling tongs',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'tongs.png',
                    'size' => '65000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Components',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic tongs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                        'childs' => [
                            [
                                'name' => [
                                    'en' => 'Hydraulic tong power unit',
                                    'ru' => '',
                                    'uk' => '',
                                    'zh' => ''
                                ],
                            ]
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Tubing tongs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Casing tongs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Manual tongs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Forks',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Pipe spanner',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Chain spanner',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hinged tongs',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // chemics
            [
                'name' => [
                    'en' => 'Chemical reagents',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'chemics.png',
                    'size' => '36000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Sequestering & filtration control agents',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Mud heaver',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'LCM',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Bentonite & alternate',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Non-organic chemicals',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Lubticants & antisticking agents',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // chemLab
            [
                'name' => [
                    'en' => 'Laboratory of mud chemical-analysis',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'chem-lab-eq.png',
                    'size' => '41000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Density measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'ASG measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Viscosity measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'SSS measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'pH measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Water loss measurment equipment',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
            // jar
            [
                'name' => [
                    'en' => 'Jars',
                    'ru' => '',
                    'uk' => '',
                    'zh' => ''
                ],
                'image' => [
                    'name' => 'jars.png',
                    'size' => '35000'
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Hydromechanical',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                    [
                        'name' => [
                            'en' => 'Hydraulic',
                            'ru' => '',
                            'uk' => '',
                            'zh' => ''
                        ],
                    ],
                ]
            ],
        ];
    }

    private function getServicesSchema()
    {
        return [
            [
                'name' => [
                    'en' => 'Emergency Service',
                ]
            ],
            [
                'name' => [
                    'en' => 'Drillin Contractor',
                ]
            ],
            [
                'name' => [
                    'en' => 'Air Emissions',
                ]
            ],
            [
                'name' => [
                    'en' => 'Geological Well Survey',
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Selection Core',
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Logging',
                        ]
                    ],
                ]
            ],
            [
                'name' => [
                    'en' => 'Flaw Detection and Certification',
                ]
            ],
            [
                'name' => [
                    'en' => 'Bit Service',
                ]
            ],
            [
                'name' => [
                    'en' => 'Downhole Motor Service',
                ]
            ],
            [
                'name' => [
                    'en' => 'Groundings',
                ]
            ],
            [
                'name' => [
                    'en' => 'Directional Drilling',
                ]
            ],
            [
                'name' => [
                    'en' => 'Casing Equipment',
                ]
            ],
            [
                'name' => [
                    'en' => 'Security',
                ]
            ],
            [
                'name' => [
                    'en' => 'Blow owt prevenstion',
                ]
            ],
            [
                'name' => [
                    'en' => 'Personnel selection / HR',
                ]
            ],
            [
                'name' => [
                    'en' => 'Pipe supply',
                ]
            ],
            [
                'name' => [
                    'en' => 'Gas station / supply',
                ],
            ],
            [
                'name' => [
                    'en' => 'Heavy machinery supply / rental',
                ]
            ],
            [
                'name' => [
                    'en' => 'Building / Construction',
                ]
            ],
            [
                'name' => [
                    'en' => 'On-site Logging station',
                ]
            ],
            [
                'name' => [
                    'en' => 'Transportation / Logistics',
                ]
            ],
            [
                'name' => [
                    'en' => 'Waste Disposal',
                ],
                'childs' => [
                    [
                        'name' => [
                            'en' => 'Drilling',
                        ]
                    ],
                    [
                        'name' => [
                            'en' => 'Domestic',
                        ]
                    ],
                ]
            ],
            [
                'name' => [
                    'en' => 'Chemical Laboratory Control',
                ]
            ],
            [
                'name' => [
                    'en' => 'Cementing',
                ]
            ],
        ];
    }
}
