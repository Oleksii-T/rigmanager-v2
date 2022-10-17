<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
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
                'slug' => 'purpose',
                'translations' => [
                    'question' => [
                        'en' => 'What for this website?'
                    ],
                    'answer' => [
                        'en' => 'We introduce Ukrainian the first specialized website for the cooperation of oil and gas companies of Ukraine.
                        In other words, we are presenting a Bulletin board for companies looking for clients.
                        Sale, purchase, rent, services.
                        On our website, you can find catalog of drilling equipment and services.
                        We are aiming to inform Ukraine drilling market place about all its participants and set up convenient and reliable cooperation between companies.
                        For abroad equipment/service providers we introduce our website as a place where they can find a Ukraine customer easily.
                        Our long-term goal is to become an international platform for trade and services advertising among all oil and gas companies in the world.'
                    ],
                ]
            ],
            [
                'slug' => 'why',
                'translations' => [
                    'question' => [
                        'en' => 'Why am I need this?'
                    ],
                    'answer' => [
                        'en' => '<p>If you are company management:</p>
                        <ol>
                            <li>Reducing purchasing costs</li>
                            <li>Increase the sales level</li>
                            <li>Get a service market analysis tool, detailed to any level including prices</li>
                        </ol>
                        <p>If you are supply department:</p>
                        <ol>
                            <li>Simplify and control the procurement/sales process</li>
                            <li>Control of intime supply</li>
                            <li>To establish contacts between the services of oil and gas companies of Ukraine</li>
                            <li>Purchase planning</li>
                            <li>Control the relevance of procurement</li>
                            <li>Tenders by simplified procedure</li>
                        </ol>
                        <p>If you are sales department:</p>
                        <ol>
                            <li>Publication announcements of sales department</li>
                            <li>Sales planning</li>
                            <li>Simplify and control the purchasing/selling process</li>
                            <li>Publication of anonymous announcements if desired</li>
                        </ol>'
                    ],
                ]
            ],
            [
                'slug' => 'benefits',
                'translations' => [
                    'question' => [
                        'en' => 'Benefits'
                    ],
                    'answer' => [
                        'en' => '<p></p>
                        <ol>
                            <li>Announcements exclusively in the oil and gas sphere;</li>
                            <li>Safeguarding of personal information. The ability to work anonymously;</li>
                            <li>Simple and intuitive interface is not overloaded with unnecessary buttons and functionality;</li>
                            <li>Fast registration. Only e-mail and password are required;</li>
                            <li>Fast publication of posts. Only title and description are required;</li>
                            <li>An adapted form for creating posts for the oil and gas sphere;</li>
                            <li>Convenient category system;</li>
                            <li>Full 3-paganism. Ukrainian, Russian, English;</li>
                            <li>Possibility of personal cooperation and getting help from representatives of the resource. Via the Internet or onsite to your office;</li>
                            <li>Mailing system - receiving notifications about new posts on a particular topic;</li>
                            <li>Ability to bulk import posts.</li>
                        </ol>'
                    ],
                ]
            ],
            [
                'slug' => 'services',
                'translations' => [
                    'question' => [
                        'en' => 'Services'
                    ],
                    'answer' => [
                        'en' => '<p></p>
						<ol>
							<li>Specialized bulletin board for representatives of the oil and gas market and only;</li>
							<li>Affiliate program;</li>
							<li>Free access to viewing posts (limited);</li>
							<li>Convenient and fast publication of posts of any complexity level;</li>
							<li>Sale of a "standard" or "pro" subscription (in the future);</li>
							<li>Informing all available and relevant goods and services in the oil and gas sphere;</li>
							<li>Resource smart search;</li>
							<li>Adaptive filtering of search queries;</li>
							<li>Convenient category system;</li>
							<li>Ability to save posts to the "Favorites" list for quick access;</li>
							<li>Get contacts of authors;</li>
							<li>Customizable "Mailer" to receive notifications of new posts on a particular topic;</li>
							<li>Auto-translator of interface and announcements into Russian, Ukrainian and English;</li>
							<li>Bulk import of posts to the site using a special import file;</li>
							<li>Detailed statistics of views;</li>
							<li>Mobile version of the site;</li>
							<li>Providing assistance in publishing your posts;</li>
							<li>Help adapting your lists / specifications for our import file and resource;</li>
							<li>Optimization of your posts to increase the potential search results;</li>
							<li>The ability to publish incognito.</li>
						</ol>'
                    ],
                ]
            ],
            [
                'slug' => 'cost',
                'translations' => [
                    'question' => [
                        'en' => 'Cost'
                    ],
                    'answer' => [
                        'en' => '<p>Our resource is only gaining popularity, therefore, all functionality is completely free.
                        A list of future paid subscriptions is available <a href="https://rigmanager.com.ua/en/plans">here</a>.
                        We do not influence, control or participate in contracts concluding between our clients.
                        The list of paid functionality will grow, for example, providing analytics for your niche.</p>'
                    ],
                ]
            ],
            [
                'slug' => 'buy',
                'translations' => [
                    'question' => [
                        'en' => 'I need to buy equipment or find a service'
                    ],
                    'answer' => [
                        'en' => '<p>You may use the <a class="link" href="https://rigmanager.com.ua/en/catalog">Catalog</a> of categories or use the search bar and you find what you looking for!
                        You could add a post to favourites list so that you won\'t lose it.
                        If you can not find proper equipment/service then subscribe for Mailer and you will always know when appropriate post is published.</p>'
                    ],
                ]
            ],
            [
                'slug' => 'sell',
                'translations' => [
                    'question' => [
                        'en' => 'I need to sell equipment or advertise my service'
                    ],
                    'answer' => [
                        'en' => '<p>All you need to do, is <a class="link" href="https://rigmanager.com.ua/en/posts/create">create and publish your post</a>, by filling input fields and after just wait for your clients!</p>'
                    ],
                ]
            ],
            [
                'slug' => 'mailer',
                'translations' => [
                    'question' => [
                        'en' => 'What is Mailer?'
                    ],
                    'answer' => [
                        'en' => '<p>Mailer is smart mailing engine for notifying the user about new posts.
                        You can create several individual Mailers e.g. subscribe to different authors or categories.
                        Every day you will receive notifications to your e-mail(login) if suitable posts have been published over the past day.</p>'
                    ],
                ]
            ],
            [
                'slug' => 'create-mailer',
                'translations' => [
                    'question' => [
                        'en' => 'How to create new Mailer'
                    ],
                    'answer' => [
                        'en' => '<p>New Mailer can be created from two pages.
                        First is page of exact post by clicking "Add author to Mailer" button.
                        Second is any search page (e.g. <a class="link" href="https://rigmanager.com.ua/en/search?type=equipment-sell">Sell of drilling
                        equipment</a> Or <a class="link" href="https://rigmanager.com.ua/en/burovye-truby">Drilling pipes</a>) by clicking "Add this request to Mailer" button below filters.
                        After Mailer been created it can be reached and configured on its own page - <a href="https://rigmanager.com.ua/en/profile/mailer" class="link">Mailer</a></p>'
                    ],
                ]
            ],
            [
                'slug' => 'auto-translator',
                'translations' => [
                    'question' => [
                        'en' => 'What is Auto-Translator?'
                    ],
                    'answer' => [
                        'en' => '<p>Auto-Translator is automatic title and description translation in Ukrainian and Russian languages for your newly created posts.
                        It is made up for increasing chances of finding your clients.
                        You will be able to see the result of the post translation by selecting the interface language and going to your post page.
                        The post translations can be found (to view or edit) on a special page, the link to which is on the edit post page.
                        The user who opens the post will always have the possibility to see the original title and description if he reviews the post not in the original language.
                        We are updating and improving our Auto-Translator to achieve the best translation result.
                        Currently, we are using Phrase-Based Google Cloud Translation Model.</p>'
                    ],
                ]
            ],
            [
                'slug' => 'import',
                'translations' => [
                    'question' => [
                        'en' => 'What is bulk posts import?'
                    ],
                    'answer' => [
                        'en' => '<p>Sometimes users want to publish a lot of posts. We understand, that create them one by one will take some time.
                        To solve such an issue we provide the Bulk posts import system.
                        All you need to do is fill the import file with according to the rules and submit it.
                        Available only for Pro users.
                        <a href="https://rigmanager.com.ua/posts/create/import">Posts import</a>
                        <a href="https://rigmanager.com.ua/import/rules">Posts import policy</a>

                        In addition, our team can help you adapt and publish your equipment list for our platform. Contact us for more details <a href="https://rigmanager.com.ua/contact-us">here</a>.</p>'
                    ],
                ]
            ],
            [
                'slug' => 'partners',
                'translations' => [
                    'question' => [
                        'en' => 'What is an Affiliate Program?'
                    ],
                    'answer' => [
                        'en' => '<p>The affiliate program with rigmanager.com.ua allows you to increase your base of potential customers.
                        Partners are provided with:</p>
						<ol>
							<li>Place in the "Partners" block on the home page of the site;</li>
							<li>Special mark on posts;</li>
							<li>Technical support 24/7;</li>
							<li>Help with posts managing;</li>
							<li>Removing limits. (max. no. of posts, max. no. of urgent posts); </li>
							<li>Possibility to advertise your company in post text;</li>
							<li>Priority for your posts;</li>
							<li>Impact on the development of our resource.</li>
						</ol>
						<p>If you are interested or want more information, please contact us <a href="https://rigmanager.com.ua/en/contact-us">here</a>.</p>'
                    ],
                ]
            ],
        ];

        foreach ($schemas as $i => $schema) {
            $faq = Faq::updateOrCreate(
                [
                    'slug' => $schema['slug']
                ],
                [
                    'slug' => $schema['slug'],
                    'order' => $i+1
                ]
            );

            $faq->saveTranslations($schema['translations']);
        }
    }
}
