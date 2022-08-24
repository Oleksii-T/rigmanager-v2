<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Attachment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shemas = [
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

        foreach ($shemas as $shema) {
            $model = Category::create([
                'category_id' => null,
                'is_active' => true
            ]);
            $model->saveTranslations([
                'name' => $shema['name'],
                'slug' => $this->makeSlugs($shema['name'])
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
                'is_active' => true
            ]);
            $model->saveTranslations([
                'name' => $child['name'],
                'slug' => $this->makeSlugs($child['name'])
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
}
