<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageItem;
use App\Enums\PageItemType;
use App\Services\DeepLService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds of pages and related blocks.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'page' => [
                    'link' => '/',
                    'status' => 3,
                    'meta_title' => [
                        'en' => 'Drilling and oil and gas equipment on the rigmanagers bulletin board.',
                        'zh' => 'rigmanagers 公告栏上的钻井和油气设备。',
                    ],
                    'meta_description' => [
                        'en' => 'rigmangers.com - specialized bulletin board for participants in the oil and gas market. Buying, selling, renting and services.',
                        'zh' => 'rigmangers.com - 面向石油和天然气市场参与者的专业公告栏。买卖、租赁和服务。',
                    ],
                ],
                'items' => [
                    'header' => [
                        'catalog' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Catalog',
                                'zh' => ''
                            ]
                        ],
                        'eq-catalog' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Equipment Catalog',
                                'zh' => ''
                            ]
                        ],
                        'se-catalog' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Service Catalog',
                                'zh' => ''
                            ]
                        ],
                        'categories' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Categories',
                                'zh' => '类别'
                            ]
                        ],
                        'eq-categories' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Equipment Categories',
                                'zh' => '设备类别'
                            ]
                        ],
                        'se-categories' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Service Categories',
                                'zh' => '服务类别'
                            ]
                        ],
                        'search-service' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Search service',
                                'zh' => ''
                            ]
                        ],
                        'search-equipment' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Search equipment',
                                'zh' => ''
                            ]
                        ],
                        'profile' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Profile',
                                'zh' => ''
                            ]
                        ],
                        'my-posts' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'My posts',
                                'zh' => ''
                            ]
                        ],
                        'favourites' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Favourites',
                                'zh' => ''
                            ]
                        ],
                        'mailer' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Mailer',
                                'zh' => ''
                            ]
                        ],
                        'my-subscription' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'My subscription',
                                'zh' => ''
                            ]
                        ],
                        'sign-out' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Sigh Out',
                                'zh' => ''
                            ]
                        ],
                        'sign-in' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Sign In',
                                'zh' => ''
                            ]
                        ],
                        'add-post' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Add Post',
                                'zh' => ''
                            ]
                        ],
                        'add-eq-post' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Add Equipment Post',
                                'zh' => ''
                            ]
                        ],
                        'add-se-post' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Add Service Post',
                                'zh' => ''
                            ]
                        ],
                        'intro-sell-eq' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Sell of drilling equipment',
                                'zh' => ''
                            ]
                        ],
                        'intro-buy-eq' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Buy and loan of drilling equipment',
                                'zh' => ''
                            ]
                        ],
                        'intro-se' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Providing and requesting of services',
                                'zh' => ''
                            ]
                        ],
                    ],
                    'footer' => [
                        'copyright' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Specialized platform for cooperation of oil and gas companies. All rights reserved.',
                                'zh' => ''
                            ]
                        ],
                        'about' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'About',
                                'zh' => ''
                            ]
                        ],
                        'news' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'News',
                                'zh' => ''
                            ]
                        ],
                        'catalog' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Catalog',
                                'zh' => ''
                            ]
                        ],
                        'contact-us' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Contact Us',
                                'zh' => ''
                            ]
                        ],
                        'faq' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'FAQ',
                                'zh' => ''
                            ]
                        ],
                        'terms' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Terms of Service',
                                'zh' => ''
                            ]
                        ],
                        'privacy' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Privacy Policy',
                                'zh' => ''
                            ]
                        ],
                        'sitemap' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Site map',
                                'zh' => ''
                            ]
                        ],
                    ],
                    'top' => [
                        'title' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Specialized platform for cooperation of oil and gas companies',
                                'zh' => '石油和天然气公司的专业合作平台'
                            ]
                        ],
                        'sell-eq' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Sell of drilling equipment',
                                'zh' => ''
                            ]
                        ],
                        'provide-se' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Providing and requesting of services',
                                'zh' => ''
                            ]
                        ],
                        'buy-eq' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Buy and loan of drilling equipment',
                                'zh' => ''
                            ]
                        ],
                        'search' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Search equipment',
                                'zh' => ''
                            ]
                        ],
                    ],
                    'categories' => [
                        'equipment' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Equipment',
                                'zh' => ''
                            ]
                        ],
                        'service' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Service',
                                'zh' => ''
                            ]
                        ],
                    ],
                    'latest-posts' => [
                        'new-posts' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'New posts',
                                'zh' => ''
                            ]
                        ],
                        'more-posts' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'More posts',
                                'zh' => ''
                            ]
                        ],
                    ],
                    'information' => [
                        'content' => [
                            'type' => PageItemType::RICHTEXT,
                            'value' => [
                                'en' => 'Online announcements of any type and complexity in oil&gas sphere. Sale, purchase, services. On the rigmanager platform you can quickly and conveniently publish your offers or easily find an announcement you are interested in!

                                Market analysis, sales planning, simplification of the process and increase of the level of purchases/sales, control of relevance of purchases and timely supply - all this on the rigmanager platform!',
                                'zh' => ''
                            ]
                        ],
                    ],
                ]
            ],
            [
                'page' => [
                    'link' => 'categories',
                    'status' => 3,
                    'meta_title' => [
                        'en' => 'Catalog of categories of drilling and oil and gas equipment and services.',
                        'zh' => '',
                    ],
                    'meta_description' => [
                        'en' => 'A complete catalog of all categories of equipment and services in the field of drilling and oil and gas. Drill pipes, drilling rigs, mud pumps.}}',
                        'zh' => '',
                    ],
                ],
                'items' => [
                    'content' => [
                        'title' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Equipment Categories',
                                'zh' => ''
                            ]
                        ],
                        'side-text' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Main equipment categories',
                                'zh' => ''
                            ]
                        ],
                        'text' => [
                            'type' => PageItemType::RICHTEXT,
                            'value' => [
                                'en' => 'The full list of equipment and service categories for fast navigation and searching of posts.',
                                'zh' => ''
                            ]
                        ],
                    ]
                ]
            ],
            [
                'page' => [
                    'link' => 'catalog',
                    'status' => 3,
                    'meta_title' => [
                        'en' => 'Oil&Gas equipment for sale | rigmanagers.com',
                        'zh' => '',
                    ],
                    'meta_description' => [
                        'en' => 'Find new or used drilling equipment for sale or rent at rigmanagers.com',
                        'zh' => '',
                    ],
                ],
                'items' => [
                    'content' => [
                        'title' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Equipment Catalog',
                                'zh' => ''
                            ]
                        ],
                        'text' => [
                            'type' => PageItemType::RICHTEXT,
                            'value' => [
                                'en' => '<b>Browse Top Oil & Gas Equipment</b> Discover the best deals on Rig & Accessories, Well Control Equipment, and more. Upgrade your oilfield operations today!',
                                'zh' => ''
                            ]
                        ],
                        'read-more' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Read more',
                                'zh' => ''
                            ]
                        ],
                        'sub-text' => [
                            'type' => PageItemType::RICHTEXT,
                            'value' => [
                                'en' => '
                                    <h2>High-Quality Oil &amp; Gas Equipment for Sale</h2>
                                    <p>
                                        Our catalog features a wide selection of top-tier Oil &amp; Gas equipment, including Rig &amp; Accessories, Well Control Equipment, Solid Control Equipment, and more. 
                                        Each product is designed to meet the highest standards of quality and reliability, ensuring optimal performance in the field. 
                                        Whether you\'re in need of Drill String, Handling Tools, or Downhole Tools, we have you covered.  
                                    </p>
                                    <p>
                                        Our range also includes Mud Pump &amp; Spare Parts, Production Equipment &amp; OCTG, Wellhead Equipment, Flowline Products, and Others &amp; Spare parts. 
                                        Additionally, we offer Coil Tubing, Vehicles, and various accessories to meet all your oilfield needs. 
                                        Browse our catalog today to find the perfect equipment for your operation!
                                    </p>
                                ',
                                'zh' => ''
                            ]
                        ],
                    ],
                    'filters' => [
                        'title' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => 'Filters',
                                'zh' => ''
                            ]
                        ],
                    ],
                ]
            ],
            [
                'page' => [
                    'link' => '',
                    'status' => 3,
                    'meta_title' => [
                        'en' => '',
                        'zh' => '',
                    ],
                    'meta_description' => [
                        'en' => '',
                        'zh' => '',
                    ],
                ],
                'items' => [
                    '' => [
                        '' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => '',
                                'zh' => ''
                            ]
                        ],
                    ]
                ]
            ],
            [
                'page' => [
                    'link' => '',
                    'status' => 3,
                    'meta_title' => [
                        'en' => '',
                        'zh' => '',
                    ],
                    'meta_description' => [
                        'en' => '',
                        'zh' => '',
                    ],
                ],
                'items' => [
                    '' => [
                        '' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => '',
                                'zh' => ''
                            ]
                        ],
                    ]
                ]
            ],
            [
                'page' => [
                    'link' => '',
                    'status' => 3,
                    'meta_title' => [
                        'en' => '',
                        'zh' => '',
                    ],
                    'meta_description' => [
                        'en' => '',
                        'zh' => '',
                    ],
                ],
                'items' => [
                    '' => [
                        '' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => '',
                                'zh' => ''
                            ]
                        ],
                    ]
                ]
            ],
            [
                'page' => [
                    'link' => '',
                    'status' => 3,
                    'meta_title' => [
                        'en' => '',
                        'zh' => '',
                    ],
                    'meta_description' => [
                        'en' => '',
                        'zh' => '',
                    ],
                ],
                'items' => [
                    '' => [
                        '' => [
                            'type' => PageItemType::TEXT,
                            'value' => [
                                'en' => '',
                                'zh' => ''
                            ]
                        ],
                    ]
                ]
            ]
        ];

        foreach ($pages as $pageAll) {
            if (!$page['link']) {
                continue;
            }

            $page = $pageAll['page'];
            $p = Page::updateOrCreate(
                [
                    'link' => $page['link']
                ],
                [
                    'status' => $page['status']
                ]
            );

            $p->saveTranslations($page);

            foreach ($pageAll['items']??[] as $group => $items) {
                if (!$group) {
                    continue;
                }
                foreach ($items as $itemName => $itemData) {
                    if (!$itemName) {
                        continue;
                    }
                    $pageItem = PageItem::updateOrCreate(
                        [
                            'page_id' => $p->id,
                            'group' => $group,
                            'name' => $itemName
                        ],
                        [
                            'type' => $itemData['type'],
                        ]
                    );

                    $translations = $itemData['value'];
                    $translator = new DeepLService;

                    foreach ($translations as $locale => $text) {
                        if ($text) {
                            continue;
                        }

                        // $translations[$locale] = $translator->translate($text, $locale);
                    }

                    $pageItem->saveTranslations(['value' => $translations]);
                }
            }
        }

    }
}
