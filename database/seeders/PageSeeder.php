<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Slide;
use App\Models\Work;
use App\Models\Service;
use App\Models\CatalogCategory;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Главная',
                'slug' => '/',
                'meta_description' => Setting::get('seo.default_description'),
                'is_published' => true,
                'is_homepage' => true,
                'content' => $this->buildHomeContent(),
            ],
            [
                'title' => 'Каталог изображений',
                'slug' => 'izobrazheniya',
                'meta_description' => 'Каталог изображений ArtDecor',
                'is_published' => true,
                'content' => $this->buildCatalogContent(),
            ],
            [
                'title' => 'Наши работы',
                'slug' => 'nashi_raboti',
                'meta_description' => 'Портфолио работ ArtDecor',
                'is_published' => true,
                'content' => $this->buildWorksContent(),
            ],
            [
                'title' => 'Услуги',
                'slug' => 'uslugi',
                'meta_description' => 'Услуги ArtDecor',
                'is_published' => true,
                'content' => $this->buildServicesContent(),
            ],
            [
                'title' => 'Примерка',
                'slug' => 'primerka',
                'meta_description' => 'Примерка изображений в интерьере',
                'is_published' => true,
                'content' => $this->buildPrimerkaContent(),
            ],
            [
                'title' => 'Контакты',
                'slug' => 'contacts',
                'meta_description' => 'Контакты ArtDecor',
                'is_published' => true,
                'content' => $this->buildContactsContent(),
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('Создано ' . count($pages) . ' страниц');
    }

    private function buildHomeContent(): array
    {
        $slides = Slide::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $content = [];

        // Hero slider block
        $heroSlides = $slides->map(fn($s) => [
            'image' => $s->getFirstMediaUrl('slides', 'hero'),
            'title' => $s->title,
            'subtitle' => $s->subtitle,
        ])->toArray();

        $content[] = [
            'type' => 'hero',
            'settings' => [
                'title' => $slides->first()?->title ?? 'ArtDecor',
                'subtitle' => $slides->first()?->subtitle ?? '3D-фрески на заказ',
                'btn_text' => 'Смотреть каталог',
                'btn_url' => '/izobrazheniya',
                'height' => 'medium',
                'overlay' => true,
                'slides' => $heroSlides,
            ],
        ];

        // About text
        $content[] = [
            'type' => 'text',
            'settings' => [
                'content' => '<h2>О нас</h2><p>ArtDecor — ведущий производитель 3D-фресок и интерьерных решений. Мы создаём уникальные изображения для вашего интерьера.</p>',
                'alignment' => 'center',
                'max_width' => 'narrow',
            ],
        ];

        // CTA
        $content[] = [
            'type' => 'cta',
            'settings' => [
                'title' => 'Закажите звонок',
                'description' => 'Оставьте заявку и мы перезвоним вам в течение 2 часов',
                'btn_text' => 'Заказать',
                'btn_url' => '#callback',
                'background_color' => '#E1323D',
                'text_color' => '#FFFFFF',
            ],
        ];

        return $content;
    }

    private function buildCatalogContent(): array
    {
        $categories = CatalogCategory::orderBy('sort_order')->get();

        $content = [];

        $content[] = [
            'type' => 'text',
            'settings' => [
                'content' => '<h1 class="text-3xl font-heading font-bold">Каталог изображений</h1><p>Выберите категорию и настройте фильтры</p>',
                'alignment' => 'center',
                'max_width' => 'page',
            ],
        ];

        if ($categories->isNotEmpty()) {
            $content[] = [
                'type' => 'columns',
                'settings' => [
                    'columns_count' => min(4, $categories->count()),
                ],
            ];
        }

        return $content;
    }

    private function buildWorksContent(): array
    {
        $content = [];

        $content[] = [
            'type' => 'text',
            'settings' => [
                'content' => '<h1 class="text-3xl font-heading font-bold">Наши работы</h1><p>Портфолио выполненных проектов</p>',
                'alignment' => 'center',
                'max_width' => 'page',
            ],
        ];

        $content[] = [
            'type' => 'gallery',
            'settings' => [
                'images' => [],
                'columns' => 3,
                'gap' => 'md',
                'lightbox' => true,
            ],
        ];

        return $content;
    }

    private function buildServicesContent(): array
    {
        $content = [];

        $content[] = [
            'type' => 'text',
            'settings' => [
                'content' => '<h1 class="text-3xl font-heading font-bold">Услуги</h1><p>Что мы предлагаем</p>',
                'alignment' => 'center',
                'max_width' => 'page',
            ],
        ];

        $services = Service::orderBy('sort_order')->get();
        foreach ($services as $service) {
            $images = $service->getFirstMediaUrl('services');
            $content[] = [
                'type' => 'columns',
                'settings' => [
                    'title' => $service->title,
                    'description' => $service->description,
                    'image' => $images,
                    'columns_count' => 2,
                ],
            ];
        }

        return $content;
    }

    private function buildPrimerkaContent(): array
    {
        return [
            [
                'type' => 'text',
                'settings' => [
                    'content' => '<h1 class="text-3xl font-heading font-bold">Примерка</h1><p>Посмотрите, как изображение будет выглядеть в вашем интерьере</p>',
                    'alignment' => 'center',
                    'max_width' => 'narrow',
                ],
            ],
            [
                'type' => 'html',
                'settings' => [
                    'html' => '<div id="primerka-app" class="min-h-[400px] border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400">Загрузите изображение и фото интерьера для примерки</div>',
                ],
            ],
        ];
    }

    private function buildContactsContent(): array
    {
        $phone = Setting::get('contacts.phone');
        $email = Setting::get('contacts.email');
        $address = Setting::get('contacts.address');

        return [
            [
                'type' => 'text',
                'settings' => [
                    'content' => '<h1 class="text-3xl font-heading font-bold">Контакты</h1>',
                    'alignment' => 'center',
                    'max_width' => 'page',
                ],
            ],
            [
                'type' => 'columns',
                'settings' => [
                    'columns_count' => 2,
                ],
            ],
            [
                'type' => 'cta',
                'settings' => [
                    'title' => 'Свяжитесь с нами',
                    'description' => "Телефон: {$phone}\nEmail: {$email}\nАдрес: {$address}",
                    'btn_text' => 'Позвонить',
                    'btn_url' => 'tel:' . preg_replace('/[^0-9+]/', '', $phone),
                    'background_color' => '#1f2937',
                    'text_color' => '#FFFFFF',
                ],
            ],
        ];
    }
}
