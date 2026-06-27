<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $homepage = Page::where('is_homepage', true)
            ->where('is_published', true)
            ->first();

        if ($homepage) {
            $content = $homepage->renderContent();
            return view('pages.dynamic', [
                'page' => $homepage,
                'content' => $content,
            ]);
        }

        // Fallback: if no homepage page exists, create one with default content
        // Use updateOrCreate to avoid unique constraint violations on slug
        $homepage = Page::updateOrCreate(
            ['slug' => '/'],
            [
                'title' => 'Главная',
                'meta_description' => 'Производство и продажа скинали, стеклянных фартуков для кухни, панно, перегородок',
                'content' => self::getDefaultHomepageContent(),
                'layout' => 'default',
                'is_published' => true,
                'is_homepage' => true,
            ]
        );

        $content = $homepage->renderContent();
        return view('pages.dynamic', [
            'page' => $homepage,
            'content' => $content,
        ]);
    }

    /**
     * Default homepage content matching the design mockup
     */
    public static function getDefaultHomepageContent(): array
    {
        return [
            // Hero slider (PromoSlider first)
            [
                'type' => 'promo_slider',
                'settings' => [
                    'slides' => [
                        [
                            'image' => '/images/promo/2023-11-25-15-00-49-14-scaled.jpeg',
                            'title' => 'Скинали с подсветкой',
                            'text' => 'Световые панели премиум-класса по цене стандарта. Создайте уникальную атмосферу на своей кухне.',
                        ],
                        [
                            'image' => '/images/promo/photo_2024-01-10_14-11-52.jpg',
                            'title' => 'Примерить онлайн',
                            'text' => 'Воспользуйтесь нашим онлайн-конфигуратором, чтобы подобрать идеальный скинали для вашей кухни.',
                        ],
                        [
                            'image' => '/images/promo/s2.jpeg',
                            'title' => 'Акция на световые скинали!',
                            'text' => 'Ограниченная акция — скинали со скидкой 10%! Не упустите возможность создать уют в своем доме по выгодной цене.',
                        ],
                    ],
                ],
            ],
            // Hero slider
            [
                'type' => 'hero',
                'settings' => [
                    'title' => 'ArtDecor',
                    'subtitle' => 'Стеклянные фартуки для кухни (скинали) на заказ',
                    'btn_text' => 'Смотреть каталог',
                    'btn_url' => '/izobrazheniya',
                    'height' => 'large',
                    'overlay' => true,
                ],
            ],
            // CTA banner
            [
                'type' => 'cta',
                'settings' => [
                    'title' => 'Акция на световые скинали!',
                    'description' => 'Ограниченная акция — скинали со скидкой 10%! Не упустите возможность создать уют в своем доме по выгодной цене.',
                    'btn_text' => 'Подробнее',
                    'btn_url' => '/uslugi',
                    'background_color' => '#F5F5F5',
                    'text_color' => '#333333',
                ],
            ],
            // Text section: Elegant design
            [
                'type' => 'text',
                'settings' => [
                    'content' => '<h2>Элегантный дизайн любой комнаты</h2><p>Уникальность и красота – вот что отличает изделия из стекла от компании ArtDecor. Мы специализируемся на производстве стен, дверей, потолков и фартуков для кухни (скинали) из стекла в Москве. <a href="/nashi_raboti/">Наши изделия</a> не просто функциональны, они еще и великолепно смотрятся благодаря уникальной подсветке, которая добавляет цвета и создает эффект объемности на стекле.</p><p>Вы можете придать своей кухне или любому другому помещению роскошный вид, <a href="/izobrazheniya/">выбрав из нашего широкого ассортимента</a>. У нас есть разнообразные дизайны, цвета и размеры, чтобы удовлетворить любой вкус и требования.</p>',
                    'alignment' => 'left',
                    'max_width' => 'page',
                ],
            ],
            // Features / advantages
            [
                'type' => 'text',
                'settings' => [
                    'content' => '<div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-8"><div class="text-center"><div class="feature-icon"><svg viewBox="0 0 37 37" fill="none"><path d="M36.38 37H33.3V14.8H4.3V36.4C4.3 36.7 4 37 3.7 37H0.6C0.3 37 0 36.7 0 36.4V10.5C0 10.3 0.1 10 0.3 9.9L18.2 0.1C18.4 0 18.6 0 18.8 0.1L36.7 10.6C36.9 10.7 37 10.9 37 11.1V36.4C37 36.7 36.7 37 36.38 37Z" fill="currentColor"/></svg></div><h3 class="font-heading font-bold text-sm mb-1">Закалённое стекло</h3><p class="text-xs text-gray-500">Только закалённое стекло высокой прочности</p></div><div class="text-center"><div class="feature-icon"><svg viewBox="0 0 32 38" fill="none"><path d="M32 9.2C32 4.1 27.9 0 22.8 0C19.2 0 16.2 2 14.6 4.9H0.6C0.3 4.9 0 5.2 0 5.5V37.4C0 37.7 0.3 38 0.6 38H27.7C28 38 28.3 37.7 28.3 37.4V16.5C30.5 14.9 32 12.2 32 9.2Z" fill="currentColor"/></svg></div><h3 class="font-heading font-bold text-sm mb-1">Фиксированная цена</h3><p class="text-xs text-gray-500">Прозрачное ценообразование</p></div><div class="text-center"><div class="feature-icon"><svg viewBox="0 0 33 39" fill="none"><path d="M32.1 27.2L29.5 25.7L32.1 24.1C32.7 23.8 33 23.2 33 22.6C33 21.9 32.7 21.3 32.1 21L29.5 19.5L32.1 18C32.7 17.6 33 17.1 33 16.4C33 15.8 32.7 15.2 32.1 14.8L29.7 13.4C29.7 7.3 29.8 7.6 29.6 7.4C29.5 7.2 29.5 7.3 27.1 5.9C26.8 5.7 26.4 5.8 26.3 6.1C26.1 6.4 26.2 6.7 26.5 6.9L27.9 7.7L23.6 9.9C23.4 10 23.2 10.2 23.2 10.4C23.2 10.6 23.4 10.8 23.6 10.9L27.9 13.1C26.2 14 17.7 19 16.8 19.5C16.7 19.6 16.6 19.6 16.6 19.5L15.9 19.1C15.6 18.9 15.2 19 15.1 19.3C14.9 19.6 15 19.9 15.3 20.1L16 20.5C16.2 20.6 16.5 20.7 16.7 20.7C16.9 20.7 17.2 20.6 17.4 20.5L28.5 14.1L31.5 15.8C32 16.1 32 16.7 31.5 17C23.4 21.7 26.8 19.7 17 25.4C16.8 25.5 16.6 25.5 16.4 25.4L7.5 20.3C7.3 20.1 6.9 20.2 6.8 20.5C6.6 20.8 6.7 21.1 7 21.3L15.8 26.4C16.1 26.6 16.4 26.7 16.7 26.7C17 26.7 17.3 26.6 17.6 26.4L28.4 20.1L31.5 22C32 22.3 32 22.9 31.5 23.1C31.2 23.3 31.3 23.3 17 31.6C16.8 31.7 16.6 31.7 16.4 31.6L13.3 29.8C13 29.6 12.6 29.7 12.5 30C12.3 30.3 12.4 30.6 12.7 30.8L15.8 32.6C16.1 32.7 16.4 32.8 16.7 32.8C17 32.8 17.3 32.7 17.6 32.6L28.4 26.3L31.5 28.2C32 28.4 32 29.1 31.5 29.3L28.9 30.9C28.4 31.2 28.6 31.9 29.1 31.9C29.4 31.9 29.3 31.9 32.1 30.3C33.3 29.6 33.3 27.9 32.1 27.2Z" fill="currentColor"/></svg></div><h3 class="font-heading font-bold text-sm mb-1">Своё производство</h3><p class="text-xs text-gray-500">Полный цикл на собственной фабрике</p></div><div class="text-center"><div class="feature-icon"><svg viewBox="0 0 32 32" fill="none"><path d="M16 0C7.16 0 0 7.16 0 16C0 24.84 7.16 32 16 32C24.84 32 32 24.84 32 16C32 7.16 24.84 0 16 0Z" fill="currentColor"/></svg></div><h3 class="font-heading font-bold text-sm mb-1">Защитная плёнка</h3><p class="text-xs text-gray-500">Оклейка дополнительной защитной плёнкой</p></div></div>',
                    'alignment' => 'left',
                    'max_width' => 'page',
                ],
            ],
            // Tabs: Types of skinali
            [
                'type' => 'tabs',
                'settings' => [
                    'tabs' => [
                        ['title' => 'Однотонные скинали', 'content' => '<p>Классический выбор для современного интерьера. Палитра более 200 оттенков.</p>'],
                        ['title' => 'Скинали с рисунком', 'content' => '<p>УФ-печать высокого качества. Любое изображение из нашего каталога.</p>'],
                        ['title' => '3D скинали', 'content' => '<p>Декоративное стекло с трёхмерным эффектом. Создаёт уникальную атмосферу.</p>'],
                    ],
                ],
            ],
            // CTA: Online fitting
            [
                'type' => 'cta',
                'settings' => [
                    'title' => 'Примерить скинали онлайн',
                    'description' => 'Воспользуйтесь нашим онлайн-конфигуратором, чтобы подобрать идеальный скинали для вашей кухни. <a href="/primerka/" class="text-[var(--k-color-primary)] font-semibold hover:underline">Свяжитесь с нами</a> для профессиональной консультации.',
                    'btn_text' => 'Открыть каталог',
                    'btn_url' => '/primerka',
                    'background_color' => '#F5F5F5',
                    'text_color' => '#333333',
                ],
            ],
            // CTA: Request measurement
            [
                'type' => 'cta',
                'settings' => [
                    'title' => 'Оставьте заявку на замер',
                    'description' => 'Мы перезвоним в течение 5 минут и дадим вам скидку 10%',
                    'btn_text' => 'Отправить',
                    'btn_url' => '/#callback',
                    'background_color' => '#ECECEC',
                    'text_color' => '#333333',
                    'padding' => 'py-16 md:py-20',
                ],
            ],
            // Project collaboration
            [
                'type' => 'text',
                'settings' => [
                    'content' => '<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"><div><h2 class="text-3xl font-heading font-bold mb-4">Совместный проект с Никасом Сафроновым</h2><p class="text-[var(--k-color-text-secondary)] leading-relaxed mb-6">Уникальная коллаборация с известным дизайнером. Вместе мы создаём интерьерные решения, которые сочетают в себе красоту стекла и современный дизайн.</p><a href="/nashi_raboti/" class="btn-primary">Смотреть проект</a></div><div class="bg-[var(--k-color-bg-surface)] rounded-lg shadow-md aspect-[4/3]"></div></div>',
                    'alignment' => 'left',
                    'max_width' => 'page',
                ],
            ],
            // Prices
            [
                'type' => 'prices',
                'settings' => [
                    'heading' => 'Наши цены',
                    'prices' => [
                        ['name' => 'Эконом', 'price' => '4 700', 'unit' => '₽/м²', 'features' => "Стандартное стекло 6мм\nОднотонная печать\nМонтаж включён\nГарантия 1 год", 'btn_text' => 'Заказать', 'btn_url' => '/contacts', 'featured' => false],
                        ['name' => 'Стандарт', 'price' => '6 800', 'unit' => '₽/м²', 'features' => "Закалённое стекло 6мм\nУФ-печать любого рисунка\nМонтаж включён\nЗащитная плёнка\nГарантия 3 года", 'btn_text' => 'Заказать', 'btn_url' => '/contacts', 'featured' => true],
                        ['name' => 'Премиум', 'price' => '35 000', 'unit' => '₽/м²', 'features' => "Закалённое стекло 8мм\n3D-эффект или подсветка\nДизайн-проект\nПремиум монтаж\nГарантия 5 лет", 'btn_text' => 'Заказать', 'btn_url' => '/contacts', 'featured' => false],
                    ],
                ],
            ],
            // Testimonials
            [
                'type' => 'testimonials',
                'settings' => [
                    'items' => [
                        ['text' => 'Отличная компания! Сделали скинали для кухни точно в срок. Качество стекла и печати на высшем уровне.', 'author' => 'Алексей М.'],
                        ['text' => 'Очень доволен результатом. Скинали с подсветкой смотрятся потрясающе. Спасибо команде ArtDecor!', 'author' => 'Елена К.'],
                        ['text' => 'Заказывали панно для гостиной. Превзошли все ожидания! Дизайнер помог подобрать идеальный рисунок.', 'author' => 'Дмитрий С.'],
                    ],
                ],
            ],
            // Video
            [
                'type' => 'video',
                'settings' => [
                    'title' => 'АртДекор на Первом канале в передаче «Фазенда»',
                    'description' => 'Наша компания принимала участие в съёмках программы <a href="#" class="text-[var(--k-color-primary)] font-semibold hover:underline">«Фазенда»</a> на Первом канале.',
                    'background_color' => '#F5F5F5',
                ],
            ],
            // CTA: Prices
            [
                'type' => 'cta',
                'settings' => [
                    'title' => 'Наши цены',
                    'description' => 'Прозрачное ценообразование без скрытых платежей. Выберите подходящий тариф.',
                    'btn_text' => 'Смотреть цены',
                    'btn_url' => '/price',
                    'background_color' => '#F5F5F5',
                    'text_color' => '#333333',
                ],
            ],
            // Stats
            [
                'type' => 'stats',
                'settings' => [
                    'stats' => [
                        ['number' => '12', 'label' => 'лет на рынке'],
                        ['number' => '4 320+', 'label' => 'довольных клиентов'],
                        ['number' => 'от 7', 'label' => 'рабочих дней'],
                    ],
                    'background_color' => '#FFFFFF',
                ],
            ],
            // Accordion FAQ
            [
                'type' => 'accordion',
                'settings' => [
                    'items' => [
                        ['title' => 'Какие сроки изготовления скинали?', 'content' => 'Стандартный срок изготовления от 7 до 14 рабочих дней в зависимости от сложности и размера изделия.'],
                        ['title' => 'Какой у вас опыт работы?', 'content' => 'Более 12 лет мы специализируемся на производстве изделий из стекла.'],
                        ['title' => 'Какие гарантии вы даёте?', 'content' => 'Мы предоставляем гарантию от 1 до 5 лет в зависимости от выбранного тарифа.'],
                        ['title' => 'Можно ли установить скинали самостоятельно?', 'content' => 'Мы рекомендуем профессиональный монтаж, но предоставляем инструкции для самостоятельной установки.'],
                        ['title' => 'Какие способы оплаты вы принимаете?', 'content' => 'Мы принимаем наличные, безналичный расчёт, а также оплату банковской картой.'],
                        ['title' => 'Есть ли доставка в другие города?', 'content' => 'Да, мы осуществляем доставку по всей России транспортными компаниями.'],
                        ['title' => 'Как ухаживать за скинали?', 'content' => 'Для ухода используйте мягкую ткань и неабразивные моющие средства.'],
                        ['title' => 'Можно ли сделать скинали нестандартного размера?', 'content' => 'Да, мы изготавливаем изделия по индивидуальным размерам заказчика.'],
                        ['title' => 'Что входит в стоимость?', 'content' => 'Стоимость включает материалы, печать, закалку и монтаж.'],
                    ],
                ],
            ],
            // CTA: Contact
            [
                'type' => 'cta',
                'settings' => [
                    'title' => 'Остались вопросы?',
                    'description' => 'Свяжитесь с нами любым удобным способом. Мы всегда рады помочь вам с выбором идеального решения.',
                    'btn_text' => 'Связаться',
                    'btn_url' => '/kontakty',
                    'background_color' => '#F5F5F5',
                    'text_color' => '#333333',
                ],
            ],
        ];
    }
}
