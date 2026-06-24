# Инструкция по разработке ArtDecor

> **Стек:** Laravel 11 + Filament v3 + PostgreSQL + Alpine.js + Tailwind CSS  
> **Дата:** 2026-06-24  
> **Назначение:** Полный гайд для разработки проекта от установки до деплоя

---

## Содержание

1. [Общие принципы](#1-общие-принципы)
2. [Архитектура](#2-архитектура)
3. [Модели данных и миграции](#3-модели-данных-и-миграции)
4. [Filament — админ-панель](#4-filament--админ-панель)
5. [Публичные маршруты и контроллеры](#5-публичные-маршруты-и-контроллеры)
6. [Фронтенд: Blade + Alpine.js](#6-фронтенд-blade--alpinejs)
7. [Примерка (Primerka) — интерактивный визуализатор](#7-примерка-primerka--интерактивный-визуализатор)
8. [API-эндпоинты](#8-api-эндпоинты)
9. [Избранное (Favorites)](#9-избранное-favorites)
10. [Обработка заказов](#10-обработка-заказов)
11. [Обработка изображений](#11-обработка-изображений)
12. [Синхронизация с Яндекс.Диском](#12-синхронизация-с-яндексдиском)
13. [Миграция данных с WordPress](#13-миграция-данных-с-wordpress)
14. [Кэширование](#14-кэширование)
15. [Безопасность](#15-безопасность)
16. [Интеграции](#16-интеграции)
17. [SEO](#17-seo)
18. [Тестирование](#18-тестирование)
19. [Деплой](#19-деплой)
20. [Критерии готовности](#20-критерии-готовности)

---

## 1. Общие принципы

- **Чистый код**: соблюдение PSR-12, стандартов Laravel, именование согласно conventions.
- **Документирование**: все классы и методы — PHPDoc. Сложная логика — inline-комментарии.
- **Тестирование**: PHPUnit для критических компонентов (фильтрация, примерка, заказы).
- **Кэширование**: предусмотреть на всех уровнях (Redis, nginx fastcgi_cache, Blade fragments).
- **Безопасность**: CSRF, валидация, подготовленные запросы (Eloquent), bcrypt/Argon2 для паролей.
- **Типизация**: строгая типизация в PHP (declare(strict_types=1)).
- **Адаптивность**: mobile-first, Tailwind CSS responsive utilities.
- **Сборка**: Vite для CSS/JS.

---

## 2. Архитектура

### 2.1. Слои приложения

```
HTTP (браузер)
    │
    ├──→ Публичные страницы (Blade SSR + Alpine.js)
    │       └──→ API JSON (для фильтрации, избранного, заказов)
    │
    ├──→ Админ-панель (Filament / Livewire)
    │
    └──→ Artisan-команды (синхронизация, миграция, бэкап)
```

### 2.2. Структура директорий (Laravel)

```
app/
├── Console/Commands/         # Artisan-команды
│   ├── SyncYandexDisk.php    # Синхронизация с Яндекс.Диском
│   ├── ImportWordPress.php   # Импорт данных из WordPress
│   └── GenerateThumbs.php    # Пакетная генерация превью
│
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── CatalogController.php
│   │   ├── PrimerkaController.php
│   │   ├── WorkController.php
│   │   ├── ServiceController.php
│   │   ├── ContactController.php
│   │   └── Api/
│   │       ├── CatalogController.php
│   │       ├── FavoriteController.php
│   │       ├── OrderController.php
│   │       └── WorkController.php
│   │
│   ├── Requests/             # Form requests для валидации
│   └── Middleware/
│       └── SessionIdMiddleware.php  # Привязка session_id к гостям
│
├── Livewire/                 # Интерактивные компоненты (если нужны)
│
├── Models/
│   ├── CatalogImage.php
│   ├── CatalogCategory.php
│   ├── CatalogColor.php
│   ├── Work.php
│   ├── WorkCategory.php
│   ├── Service.php
│   ├── Slide.php
│   ├── Order.php
│   ├── Favorite.php
│   └── Setting.php
│
├── Filament/Resources/       # Filament ресурсы для каждой модели
│
├── Services/
│   ├── PrimerkaService.php   # Логика примерки
│   ├── FavoritesService.php  # Логика избранного
│   ├── OrderService.php      # Валидация и отправка
│   └── ImageService.php      # Ресайз, конвертация WebP
│
└── Settings/                 # Кастомные настройки сайта

database/
└── migrations/               # Миграции БД

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php     # Главный layout
│   ├── pages/
│   │   ├── home.blade.php
│   │   ├── catalog.blade.php
│   │   ├── primerka.blade.php
│   │   ├── works.blade.php
│   │   ├── services.blade.php
│   │   └── contacts.blade.php
│   └── components/
│       ├── header.blade.php
│       ├── footer.blade.php
│       ├── catalog-card.blade.php
│       ├── work-card.blade.php
│       ├── filters.blade.php
│       ├── modal.blade.php
│       └── order-form.blade.php

routes/
├── web.php                  # SSR-маршруты
└── api.php                  # JSON API
```

---

## 3. Модели данных и миграции

### 3.1. Полная схема БД

```php
// database/migrations/2024_01_01_000001_create_catalog_categories_table.php
Schema::create('catalog_categories', function (Blueprint $table) {
    $table->id();
    $table->string('slug', 100)->unique();
    $table->string('name', 255);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});

// 11 предопределённых категорий:
// abstr, animals, architecture, cities, design, food,
// flowers, nature, sea, textures, waterfalls

// ---

// database/migrations/2024_01_01_000002_create_catalog_colors_table.php
Schema::create('catalog_colors', function (Blueprint $table) {
    $table->id();
    $table->string('slug', 50)->unique();
    $table->string('name', 50);
    $table->string('hex', 7);        // #ff0000
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});

// 13 предопределённых цветов:
// red (#E74C3C), orange (#F39C12), yellow (#F1C40F), green (#2ECC71),
// blue (#3498DB), darkblue (#2C3E50), purple (#9B59B6), pink (#E91E63),
// brown (#795548), beige (#D4C5A9), white (#F5F5F5), gray (#95A5A6),
// black (#2C3E50)

// ---

// database/migrations/2024_01_01_000003_create_catalog_images_table.php
Schema::create('catalog_images', function (Blueprint $table) {
    $table->id();
    $table->string('title', 255)->nullable();         // Артикул
    $table->foreignId('category_id')->constrained('catalog_categories');
    $table->integer('width')->nullable();
    $table->integer('height')->nullable();
    $table->integer('file_size')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->softDeletes();
});

// ---

// database/migrations/2024_01_01_000004_create_catalog_image_color_table.php
Schema::create('catalog_image_color', function (Blueprint $table) {
    $table->foreignId('image_id')->constrained('catalog_images')->cascadeOnDelete();
    $table->foreignId('color_id')->constrained('catalog_colors')->cascadeOnDelete();
    $table->primary(['image_id', 'color_id']);
});

// ---

// database/migrations/2024_01_01_000005_create_work_categories_table.php
Schema::create('work_categories', function (Blueprint $table) {
    $table->id();
    $table->string('slug', 100)->unique();
    $table->string('name', 255);
    $table->timestamps();
});

// 9 категорий: vitrage, mirror, kitchen-apron, glass-panel,
// ceilings, backlit-skinny, backlit-panel, partitions, triplex, tile-panel

// ---

// database/migrations/2024_01_01_000006_create_works_table.php
Schema::create('works', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('work_categories');
    $table->string('title', 255);
    $table->text('description')->nullable();
    $table->boolean('is_featured')->default(false);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    $table->softDeletes();
});

// ---

// database/migrations/2024_01_01_000007_create_services_table.php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->string('title', 255);
    $table->text('description')->nullable();
    $table->string('icon', 100)->nullable();
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});

// ---

// database/migrations/2024_01_01_000008_create_slides_table.php
Schema::create('slides', function (Blueprint $table) {
    $table->id();
    $table->string('title', 255)->nullable();
    $table->string('subtitle', 255)->nullable();
    $table->string('link', 500)->nullable();
    $table->string('btn_text', 100)->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

// ---

// database/migrations/2024_01_01_000009_create_orders_table.php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('name', 255)->nullable();
    $table->string('phone', 20);
    $table->text('message')->nullable();
    $table->string('source', 50);              // catalog | primerka | callback | question
    $table->json('article_ids')->nullable();   // ["1234", "5678"]
    $table->string('facade_top_color', 50)->nullable();
    $table->string('facade_bottom_color', 50)->nullable();
    $table->string('status', 20)->default('new');  // new | contacted | closed
    $table->text('manager_comment')->nullable();
    $table->timestamps();
});

// ---

// database/migrations/2024_01_01_000010_create_favorites_table.php
Schema::create('favorites', function (Blueprint $table) {
    $table->id();
    $table->string('session_id', 64);           // Для гостей (основной сценарий)
    $table->foreignId('image_id')->constrained('catalog_images')->cascadeOnDelete();
    $table->timestamps();
    $table->index('session_id');
    $table->unique(['session_id', 'image_id']);
});

// ---

// database/migrations/2024_01_01_000011_create_settings_table.php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key', 100)->unique();
    $table->text('value')->nullable();
    $table->timestamps();
});

// Предопределённые ключи:
// contacts.phone, contacts.email, contacts.address, contacts.work_hours
// integrations.yandex_metrika_id, integrations.jivosite_id
// seo.default_title, seo.default_description
```

### 3.2. Eloquent-модели

```php
// app/Models/CatalogImage.php
class CatalogImage extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'title', 'category_id', 'width', 'height',
        'file_size', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Spatie Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('catalog')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(300)->height(300)
                    ->format('webp')
                    ->nonQueued();
                $this->addMediaConversion('preview')
                    ->width(800)->height(600)
                    ->format('webp')
                    ->nonQueued();
            });
    }

    // Связи
    public function category(): BelongsTo
    {
        return $this->belongsTo(CatalogCategory::class);
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(CatalogColor::class, 'catalog_image_color');
    }

    // Скоупы
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['category'] ?? null, fn($q, $v) =>
            $q->where('category_id', $v)
        )->when($filters['color'] ?? null, fn($q, $v) =>
            $q->whereHas('colors', fn($sq) => $sq->where('catalog_colors.id', $v))
        )->when($filters['search'] ?? null, fn($q, $v) =>
            $q->where('title', 'ilike', "%{$v}%")
        );
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

// Аналогично для остальных моделей с соответствующими связями
```

---

## 4. Filament — админ-панель

### 4.1. Установка и настройка

```bash
composer require filament/filament:"^3.2"
php artisan filament:install --panels

# Shield для ролей
composer require filament/spatie-laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 4.2. Ресурсы (для каждой сущности)

Для каждой модели создать Filament-ресурс с:

- **Form**: поля, загрузка изображений (SpatieMediaLibraryFileUpload), select для связей
- **Table**: колонки, фильтры, поиск, bulk actions
- **Widgets**: для дашборда (статистика заказов, последние загрузки)

```php
// app/Filament/Resources/CatalogImageResource.php
class CatalogImageResource extends Resource
{
    protected static ?string $model = CatalogImage::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Каталог';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Артикул')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('category_id')
                ->label('Категория')
                ->relationship('category', 'name')
                ->required()
                ->searchable(),

            Forms\Components\Select::make('colors')
                ->label('Цвета')
                ->multiple()
                ->relationship('colors', 'name')
                ->preload(),

            Forms\Components\Toggle::make('is_active')
                ->label('Активно')
                ->default(true),

            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->label('Изображение')
                ->collection('catalog')
                ->image()
                ->imageEditor()
                ->maxSize(10240)
                ->conversion('thumb')
                ->conversion('preview')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('sort_order')
                ->label('Сортировка')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('catalog')
                    ->conversion('thumb')
                    ->width(80)
                    ->height(80),
                Tables\Columns\TextColumn::make('title')->label('Артикул')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->label('Категория'),
                Tables\Columns\TextColumn::make('colors.name')->label('Цвета')->badge(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Активно'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('colors')
                    ->relationship('colors', 'name')
                    ->multiple(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
}
```

### 4.3. Разделы админки

| Раздел | Модель | Назначение |
|--------|--------|------------|
| **Изображения каталога** | `CatalogImage` | CRUD, загрузка, массовое редактирование |
| **Категории каталога** | `CatalogCategory` | Редактирование 11 категорий |
| **Цвета каталога** | `CatalogColor` | Редактирование 13 цветов + HEX |
| **Работы** | `Work` | CRUD портфолио |
| **Категории работ** | `WorkCategory` | Редактирование категорий |
| **Услуги** | `Service` | CRUD услуг |
| **Слайды** | `Slide` | Управление слайдером главной |
| **Заказы** | `Order` | Просмотр, статусы, фильтры, экспорт CSV |
| **Настройки** | `Settings` (кастомная страница) | Контакты, интеграции, SEO |
| **Пользователи** | `User` (Spatie Permission) | Администраторы, роли |

### 4.4. Кастомная страница настроек

```php
// app/Filament/Pages/Settings.php
class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->form->fill([
            'phone' => Setting::get('contacts.phone'),
            'email' => Setting::get('contacts.email'),
            'address' => Setting::get('contacts.address'),
            'work_hours' => Setting::get('contacts.work_hours'),
            'yandex_metrika_id' => Setting::get('integrations.yandex_metrika_id'),
            'jivosite_id' => Setting::get('integrations.jivosite_id'),
            'default_title' => Setting::get('seo.default_title'),
            'default_description' => Setting::get('seo.default_description'),
        ]);
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }
        Notification::make()->title('Настройки сохранены')->success()->send();
    }
}
```

---

## 5. Публичные маршруты и контроллеры

### 5.1. Маршруты (`routes/web.php`)

```php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PrimerkaController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/izobrazheniya', [CatalogController::class, 'index'])->name('catalog');
Route::get('/izobrazheniya/{category}/{color?}/{slug?}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/primerka', [PrimerkaController::class, 'index'])->name('primerka');
Route::get('/nashi_raboti', [WorkController::class, 'index'])->name('works');
Route::get('/uslugi', [ServiceController::class, 'index'])->name('services');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
```

### 5.2. Каталог — контроллер

```php
class CatalogController extends Controller
{
    public function index(): View
    {
        $categories = Cache::remember('catalog_categories', 3600, fn() =>
            CatalogCategory::orderBy('sort_order')->get()
        );
        $colors = Cache::remember('catalog_colors', 3600, fn() =>
            CatalogColor::orderBy('sort_order')->get()
        );

        return view('pages.catalog', compact('categories', 'colors'));
    }

    public function show(string $category, ?string $color = null, ?string $slug = null): View
    {
        $cat = CatalogCategory::where('slug', $category)->firstOrFail();

        $image = null;
        if ($slug) {
            $image = CatalogImage::where('slug', $slug)
                ->where('category_id', $cat->id)
                ->firstOrFail();
        }

        return view('pages.catalog-show', compact('cat', 'image'));
    }
}
```

### 5.3. Шаблоны (Blade)

```blade
{{-- resources/views/pages/catalog.blade.php --}}
<x-layouts.app :title="__('Каталог изображений')">
    <x-slot:header>
        @include('components.header')
    </x-slot>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-heading font-bold text-[#3C3D41] mb-8">
            Каталог изображений
        </h1>

        {{-- Фильтры --}}
        <x-filters :categories="$categories" :colors="$colors" />

        {{-- Сетка карточек --}}
        <div id="catalog-grid"
             x-data="catalogGrid()"
             x-init="init()"
             class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <template x-for="image in images" :key="image.id">
                <x-catalog-card :image="image" />
            </template>
        </div>

        {{-- Кнопка загрузки / бесконечный скролл --}}
        <div x-data="infiniteScroll()" x-init="init()" class="text-center py-8">
            <button x-show="hasMore" @click="loadMore()"
                    class="btn-primary px-8 py-3 rounded-lg">
                Загрузить ещё
            </button>
            <div x-show="loading" class="loader"></div>
        </div>
    </main>

    <x-slot:footer>
        @include('components.footer')
    </x-slot>
</x-layouts.app>
```

---

## 6. Фронтенд: Blade + Alpine.js

### 6.1. Layout (`resources/views/layouts/app.blade.php`)

```blade
<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ArtDecor' }} | Скинали и стеклянные изделия</title>

    {{-- SEO --}}
    <meta name="description" content="{{ $description ?? Setting::get('seo.default_description') }}">
    <meta property="og:title" content="{{ $title ?? Setting::get('seo.default_title') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Fonts: Montserrat + PT Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-white text-[#3C3D41] antialiased">
    {{ $header ?? '' }}
    {{ $slot }}
    {{ $footer ?? '' }}

    {{-- Jivosite --}}
    @if($jivositeId = Setting::get('integrations.jivosite_id'))
        <script src="//code.jivosite.com/widget/{{ $jivositeId }}" async></script>
    @endif

    {{-- Яндекс.Метрика --}}
    @if($metrikaId = Setting::get('integrations.yandex_metrika_id'))
        <script>
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],
            k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
            ym({{ $metrikaId }}, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true });
        </script>
    @endif
</body>
</html>
```

### 6.2. CSS (Tailwind + кастомные переменные)

```css
/* resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

:root {
    --k-color-primary: #E1323D;
    --k-color-primary-hover: #C82832;
    --k-color-secondary: #242E38;
    --k-color-bg-surface: #F9F9F9;
    --k-color-text-primary: #3C3D41;
    --k-color-text-secondary: #808285;
    --k-color-border: #EFEFEF;
    --k-color-accent: #6EC1E4;
}

@layer base {
    .font-heading { font-family: 'Montserrat', sans-serif; }
    .font-body { font-family: 'PT Sans', sans-serif; }
}

@layer components {
    .btn-primary {
        @apply inline-flex items-center justify-center px-6 py-3 rounded-lg
               font-bold text-sm text-white
               transition-all duration-300;
        background-color: var(--k-color-primary);
    }
    .btn-primary:hover {
        background-color: var(--k-color-primary-hover);
    }

    .btn-secondary {
        @apply inline-flex items-center justify-center px-6 py-3 rounded-lg
               font-bold text-sm
               transition-all duration-300;
        background-color: var(--k-color-secondary);
        color: white;
    }

    .card {
        @apply bg-white rounded-lg overflow-hidden shadow-sm
               transition-shadow duration-300;
    }
    .card:hover {
        @apply shadow-md;
    }
}
```

### 6.3. Header (шапка сайта)

```blade
{{-- resources/views/components/header.blade.php --}}
<header class="w-full bg-white relative z-50">
    {{-- Верхняя полоса --}}
    <div class="border-b border-[var(--k-color-border)]">
        <div class="max-w-[1200px] mx-auto flex justify-between items-center h-10 px-4 text-xs">
            <a href="#" class="underline hover:text-[var(--k-color-primary)]"
               x-data @click.prevent="$dispatch('open-modal', 'callback')">
                Заказать обратный звонок
            </a>
            <span class="font-bold text-sm">{{ Setting::get('contacts.phone') }}</span>
        </div>
    </div>

    {{-- Основная навигация --}}
    <div class="max-w-[1200px] mx-auto flex justify-between items-center h-16 px-4">
        {{-- Левое меню --}}
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)]">Главная</a>
            <a href="{{ route('catalog') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)]">Каталог</a>
            <a href="{{ route('works') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)]">Наши работы</a>
        </nav>

        {{-- Логотип --}}
        <a href="{{ route('home') }}" class="text-2xl font-heading font-bold text-[var(--k-color-secondary)]">
            ArtDecor
        </a>

        {{-- Правое меню --}}
        <nav class="hidden lg:flex items-center gap-6">
            <a href="{{ route('primerka') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)]">Примерка</a>
            <a href="{{ route('services') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)]">Услуги</a>
            <a href="{{ route('contacts') }}" class="text-sm font-bold uppercase hover:text-[var(--k-color-primary)]">Контакты</a>
        </nav>

        {{-- Мобильное меню (гамбургер) --}}
        <button class="lg:hidden" x-data @click="mobileMenuOpen = !mobileMenuOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</header>
```

### 6.4. Alpine.js — каталог (фильтрация + infinite scroll)

```javascript
// resources/js/app.js
import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    // Компонент сетки каталога
    Alpine.data('catalogGrid', () => ({
        images: [],
        page: 1,
        hasMore: true,
        loading: false,
        filters: {
            category: null,
            color: null,
            search: '',
        },

        init() {
            this.loadImages();
            // Слушаем изменения фильтров
            window.addEventListener('filter-change', (e) => {
                Object.assign(this.filters, e.detail);
                this.page = 1;
                this.images = [];
                this.loadImages();
            });
        },

        async loadImages() {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: this.page,
                    category: this.filters.category || '',
                    color: this.filters.color || '',
                    search: this.filters.search || '',
                });
                const { data } = await axios.get(`/api/catalog?${params}`);
                this.images = [...this.images, ...data.data];
                this.hasMore = data.current_page < data.last_page;
                this.page++;
            } catch (e) {
                console.error('Failed to load catalog:', e);
            } finally {
                this.loading = false;
            }
        },

        loadMore() {
            if (!this.loading && this.hasMore) {
                this.loadImages();
            }
        },
    }));

    // Компонент избранного
    Alpine.data('favorites', () => ({
        items: [],
        sessionId: '',

        init() {
            this.sessionId = this.getSessionId();
            this.load();
        },

        getSessionId() {
            let sid = localStorage.getItem('primerka_session');
            if (!sid) {
                sid = 'sid_' + Math.random().toString(36).substr(2, 9);
                localStorage.setItem('primerka_session', sid);
            }
            return sid;
        },

        async load() {
            try {
                const { data } = await axios.get('/api/favorites', {
                    params: { session_id: this.sessionId }
                });
                this.items = data;
            } catch (e) { console.error(e); }
        },

        async toggle(imageId) {
            const exists = this.items.find(i => i.id === imageId);
            try {
                if (exists) {
                    await axios.delete(`/api/favorites/${imageId}`, {
                        params: { session_id: this.sessionId }
                    });
                    this.items = this.items.filter(i => i.id !== imageId);
                } else {
                    await axios.post('/api/favorites', {
                        image_id: imageId,
                        session_id: this.sessionId
                    });
                    await this.load();
                }
            } catch (e) { console.error(e); }
        },

        isFavorite(imageId) {
            return this.items.some(i => i.id === imageId);
        },
    }));
});

Alpine.start();
```

---

## 7. Примерка (Primerka) — интерактивный визуализатор

### 7.1. HTML-структура

```blade
{{-- resources/views/pages/primerka.blade.php --}}
<x-layouts.app :title="__('Онлайн примерка изображений на скинали')">
    <main class="container mx-auto px-4 py-8" x-data="primerka()" x-init="init()">
        <h1 class="text-3xl font-heading font-bold text-[#3C3D41] mb-8">
            Онлайн примерка изображений на скинали
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Левая колонка: категории + цвета --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Категории --}}
                <div>
                    <h3 class="font-bold mb-3">Категории</h3>
                    <div class="space-y-1">
                        <template x-for="cat in categories">
                            <button @click="selectCategory(cat.id)"
                                    :class="{'text-[var(--k-color-primary)] font-bold': category === cat.id}"
                                    class="block text-sm hover:text-[var(--k-color-primary)] transition-colors"
                                    x-text="cat.name">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Цвет фасадов --}}
                <div>
                    <h3 class="font-bold mb-3">Цвет верхних фасадов</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="color in colors">
                            <button @click="selectTopColor(color.id)"
                                    :style="`background: ${color.hex}`"
                                    :class="{'ring-2 ring-offset-2 ring-[var(--k-color-primary)]': topColor === color.id}"
                                    class="w-8 h-8 rounded border border-gray-200 transition-all hover:scale-110">
                            </button>
                        </template>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold mb-3">Цвет нижних фасадов</h3>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="color in colors">
                            <button @click="selectBottomColor(color.id)"
                                    :style="`background: ${color.hex}`"
                                    :class="{'ring-2 ring-offset-2 ring-[var(--k-color-primary)]': bottomColor === color.id}"
                                    class="w-8 h-8 rounded border border-gray-200 transition-all hover:scale-110">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Центральная область: схема кухни --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- SVG-схема --}}
                <div class="bg-[var(--k-color-bg-surface)] rounded-lg p-6 flex justify-center">
                    <svg viewBox="0 0 600 400" class="w-full max-w-[500px]">
                        {{-- Верхние фасады --}}
                        <rect x="20" y="20" width="560" height="100"
                              :fill="selectedTopColor"
                              rx="4" stroke="#ddd" stroke-width="1"/>

                        {{-- Фартук с изображением --}}
                        <image x="40" y="130" width="520" height="140"
                               xlink:href="" x-bind:href="selectedImage?.preview"
                               preserveAspectRatio="xMidYMid slice"/>

                        {{-- Полка-бордюр --}}
                        <rect x="20" y="270" width="560" height="6"
                              fill="#ddd" rx="2"/>

                        {{-- Нижние фасады --}}
                        <rect x="20" y="280" width="560" height="100"
                              :fill="selectedBottomColor"
                              rx="4" stroke="#ddd" stroke-width="1"/>

                        {{-- Ручки --}}
                        <circle cx="100" cy="330" r="4" fill="#999"/>
                        <circle cx="300" cy="330" r="4" fill="#999"/>
                        <circle cx="500" cy="330" r="4" fill="#999"/>
                    </svg>
                </div>

                {{-- Артикул --}}
                <div class="text-center" x-show="selectedImage">
                    <p class="text-sm text-[var(--k-color-text-secondary)]">
                        Артикул: <strong class="text-[#3C3D41]" x-text="selectedImage?.title"></strong>
                    </p>
                </div>

                {{-- Кнопки действий --}}
                <div class="flex justify-center gap-4">
                    <button @click="addToFavorites()" :disabled="!selectedImage"
                            class="btn-primary px-6 py-3 rounded-lg">
                        ♥ В избранное
                    </button>
                    <button @click="openOrderForm()" :disabled="!selectedImage"
                            class="btn-secondary px-6 py-3 rounded-lg">
                        Заказать
                    </button>
                </div>

                {{-- Миниатюры изображений --}}
                <div>
                    <h3 class="font-bold mb-3">Выберите изображение</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        <template x-for="img in images">
                            <img :src="img.thumb"
                                 :class="{'ring-2 ring-[var(--k-color-primary)]': selectedImage?.id === img.id}"
                                 @click="selectImage(img)"
                                 class="w-full aspect-square object-cover rounded cursor-pointer hover:opacity-80 transition-opacity">
                        </template>
                    </div>
                </div>

                {{-- Моё избранное --}}
                <div x-data="favorites()" x-init="init()">
                    <h3 class="font-bold mb-3">
                        Моё избранное (<span x-text="items.length"></span>)
                    </h3>
                    <div class="flex gap-2 overflow-x-auto pb-2" x-show="items.length > 0">
                        <template x-for="fav in items">
                            <div class="relative flex-shrink-0">
                                <img :src="fav.thumb"
                                     @click="$dispatch('select-from-favorites', fav)"
                                     class="w-20 h-20 object-cover rounded cursor-pointer">
                                <button @click="toggle(fav.id)"
                                        class="absolute top-0 right-0 bg-white rounded-full p-0.5 text-xs shadow">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Модальное окно заказа --}}
        <x-modal name="order-form" title="Заказать">
            <form @submit.prevent="submitOrder" class="space-y-4">
                <input type="hidden" x-model="orderForm.article">
                <div>
                    <label class="block text-sm font-medium mb-1">Ваше имя</label>
                    <input type="text" x-model="orderForm.name" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Телефон</label>
                    <input type="tel" x-model="orderForm.phone" required
                           class="w-full border rounded-lg px-3 py-2"
                           placeholder="+7 (999) 123-45-67">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Сообщение</label>
                    <textarea x-model="orderForm.message" rows="3"
                              class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full">Отправить заказ</button>
            </form>
        </x-modal>
    </main>
</x-layouts.app>
```

### 7.2. Alpine.js — компонент примерки

```javascript
// resources/js/primerka.js (подключается через app.js)
Alpine.data('primerka', () => ({
    categories: [],
    colors: [],
    images: [],
    favorites: [],
    category: null,
    topColor: '#E8D5B7',
    bottomColor: '#E8D5B7',
    selectedImage: null,
    selectedTopColor: '#E8D5B7',
    selectedBottomColor: '#E8D5B7',
    orderForm: {
        name: '',
        phone: '',
        message: '',
        article: '',
    },

    async init() {
        // Загружаем категории и цвета
        const [catRes, colRes] = await Promise.all([
            axios.get('/api/catalog/categories'),
            axios.get('/api/catalog/colors'),
        ]);
        this.categories = catRes.data;
        this.colors = colRes.data;

        // Устанавливаем первую категорию по умолчанию
        if (this.categories.length > 0) {
            this.category = this.categories[0].id;
            await this.loadImages();
        }
    },

    async selectCategory(catId) {
        this.category = catId;
        this.images = [];
        await this.loadImages();
    },

    async loadImages() {
        if (!this.category) return;
        try {
            const { data } = await axios.get('/api/catalog', {
                params: { category: this.category, limit: 50 }
            });
            this.images = data.data;
        } catch (e) { console.error(e); }
    },

    selectTopColor(colorId) {
        const color = this.colors.find(c => c.id === colorId);
        if (color) {
            this.topColor = colorId;
            this.selectedTopColor = color.hex;
        }
    },

    selectBottomColor(colorId) {
        const color = this.colors.find(c => c.id === colorId);
        if (color) {
            this.bottomColor = colorId;
            this.selectedBottomColor = color.hex;
        }
    },

    selectImage(img) {
        this.selectedImage = img;
    },

    addToFavorites() {
        if (!this.selectedImage) return;
        window.dispatchEvent(new CustomEvent('toggle-favorite', {
            detail: { imageId: this.selectedImage.id }
        }));
    },

    openOrderForm() {
        if (!this.selectedImage) return;
        this.orderForm.article = this.selectedImage.title;
        window.dispatchEvent(new CustomEvent('open-modal', {
            detail: 'order-form'
        }));
    },

    async submitOrder() {
        try {
            await axios.post('/api/order', {
                name: this.orderForm.name,
                phone: this.orderForm.phone,
                message: this.orderForm.message,
                source: 'primerka',
                article_ids: [this.orderForm.article],
                facade_top_color: this.selectedTopColor,
                facade_bottom_color: this.selectedBottomColor,
            });
            this.orderForm = { name: '', phone: '', message: '', article: '' };
            window.dispatchEvent(new CustomEvent('close-modal'));
            alert('Спасибо! Заказ принят. Мы перезвоним вам в течение 2 часов.');
        } catch (e) {
            alert('Ошибка при отправке. Пожалуйста, попробуйте позже.');
        }
    },
}));
```

---

## 8. API-эндпоинты

### 8.1. Маршруты (`routes/api.php`)

```php
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WorkController;

Route::prefix('catalog')->group(function () {
    Route::get('/', [CatalogController::class, 'index']);          // GET /api/catalog?category=&color=&search=&page=
    Route::get('/categories', [CatalogController::class, 'categories']); // GET /api/catalog/categories
    Route::get('/colors', [CatalogController::class, 'colors']);       // GET /api/catalog/colors
});

Route::get('/works', [WorkController::class, 'index']);          // GET /api/works?category=&page=

Route::prefix('favorites')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);        // GET /api/favorites?session_id=
    Route::post('/', [FavoriteController::class, 'store']);       // POST /api/favorites {image_id, session_id}
    Route::delete('/{imageId}', [FavoriteController::class, 'destroy']); // DELETE /api/favorites/{imageId}?session_id=
});

Route::post('/order', [OrderController::class, 'store']);         // POST /api/order {name, phone, message, source, article_ids}
```

### 8.2. Контроллер каталога (API)

```php
class CatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = 12;
        $images = CatalogImage::active()
            ->with(['category', 'colors', 'media'])
            ->filter($request->only(['category', 'color', 'search']))
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $images->getCollection()->transform(function ($image) {
            return [
                'id' => $image->id,
                'title' => $image->title,
                'category' => $image->category?->name,
                'thumb' => $image->getFirstMediaUrl('catalog', 'thumb'),
                'preview' => $image->getFirstMediaUrl('catalog', 'preview'),
                'original' => $image->getFirstMediaUrl('catalog'),
                'is_favorite' => false, // фронт заполняет
            ];
        });

        return response()->json($images);
    }

    public function categories(): JsonResponse
    {
        return response()->json(
            CatalogCategory::orderBy('sort_order')->get(['id', 'name', 'slug'])
        );
    }

    public function colors(): JsonResponse
    {
        return response()->json(
            CatalogColor::orderBy('sort_order')->get(['id', 'name', 'slug', 'hex'])
        );
    }
}
```

### 8.3. Контроллер заказов

```php
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->create($request->validated());

        // Отправка email менеджеру
        Mail::to(config('mail.admin_address'))
            ->send(new OrderCreatedMail($order));

        return response()->json([
            'success' => true,
            'message' => 'Заказ принят. Мы перезвоним вам в течение 2 часов.',
        ]);
    }
}

// app/Http/Requests/OrderRequest.php
class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|regex:/^[\+\d\s\-\(\)]+$/',
            'message' => 'nullable|string|max:5000',
            'source' => 'required|string|in:catalog,primerka,callback,question',
            'article_ids' => 'nullable|array',
            'article_ids.*' => 'string',
            'facade_top_color' => 'nullable|string',
            'facade_bottom_color' => 'nullable|string',
        ];
    }
}
```

---

## 9. Избранное (Favorites)

### 9.1. Сервис

```php
class FavoritesService
{
    public function getFavorites(string $sessionId): Collection
    {
        return Favorite::where('session_id', $sessionId)
            ->with('image.media')
            ->get()
            ->pluck('image')
            ->map(fn($image) => [
                'id' => $image->id,
                'title' => $image->title,
                'thumb' => $image->getFirstMediaUrl('catalog', 'thumb'),
                'preview' => $image->getFirstMediaUrl('catalog', 'preview'),
            ]);
    }

    public function toggle(int $imageId, string $sessionId): array
    {
        $exists = Favorite::where('session_id', $sessionId)
            ->where('image_id', $imageId)
            ->exists();

        if ($exists) {
            Favorite::where('session_id', $sessionId)
                ->where('image_id', $imageId)
                ->delete();
            return ['action' => 'removed'];
        }

        Favorite::create([
            'session_id' => $sessionId,
            'image_id' => $imageId,
        ]);
        return ['action' => 'added'];
    }
}
```

### 9.2. Сессия для гостей

Гости идентифицируются через `session_id`, который генерируется на фронте и хранится в `localStorage`. Передаётся в каждом API-запросе.

---

## 10. Обработка заказов

### 10.1. OrderService

```php
class OrderService
{
    public function create(array $data): Order
    {
        return Order::create([
            'name' => $data['name'] ?? null,
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'source' => $data['source'],
            'article_ids' => json_encode($data['article_ids'] ?? []),
            'facade_top_color' => $data['facade_top_color'] ?? null,
            'facade_bottom_color' => $data['facade_bottom_color'] ?? null,
            'status' => 'new',
        ]);
    }
}
```

### 10.2. Mailable

```php
// app/Mail/OrderCreatedMail.php
class OrderCreatedMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Новый заказ #{$this->order->id} с сайта ArtDecor",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-created',
        );
    }
}
```

---

## 11. Обработка изображений

### 11.1. Spatie Media Library

```bash
composer require spatie/laravel-medialibrary:"^11.0"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
php artisan migrate
```

### 11.2. Конфигурация

```php
// config/medialibrary.php
return [
    'disk_name' => env('MEDIA_DISK', 'public'),
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,
    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
    ],
    'queue_conversions_by_default' => true,
    'conversions' => [
        'thumb' => ['width' => 300, 'height' => 300, 'format' => 'webp', 'quality' => 80],
        'preview' => ['width' => 800, 'height' => 600, 'format' => 'webp', 'quality' => 85],
    ],
];
```

### 11.3. Хранилище

```env
# .env
MEDIA_DISK=yandex-cloud
FILESYSTEM_DISK=yandex-cloud

YC_KEY_ID=your_key_id
YC_KEY_SECRET=your_key_secret
YC_BUCKET=artdecor-images
YC_REGION=ru-central1
YC_CDN_URL=https://cdn.artdecor.ru
```

### 11.4. Пакетная генерация превью

```php
// app/Console/Commands/GenerateThumbs.php
class GenerateThumbs extends Command
{
    protected $signature = 'artdecor:generate-thumbs {--model=catalog : catalog|work}';

    public function handle(): void
    {
        $model = $this->option('model') === 'work' ? Work::class : CatalogImage::class;
        $collection = $this->option('model') === 'work' ? 'works' : 'catalog';

        $this->output->title("Generating {$collection} thumbnails...");

        $model::chunk(50, function ($items) use ($collection) {
            foreach ($items as $item) {
                $item->getMedia($collection)->each(function ($media) {
                    $media->markAsConversionGenerated('thumb', true);
                    $media->markAsConversionGenerated('preview', true);
                });
                $this->output->writeln("  Processed: {$item->id}");
            }
        });

        $this->output->success('Done!');
    }
}
```

---

## 12. Синхронизация с Яндекс.Диском

```php
// app/Console/Commands/SyncYandexDisk.php
class SyncYandexDisk extends Command
{
    protected $signature = 'artdecor:sync-yandex {--dry-run : Preview only}';
    protected $description = 'Sync new images from Yandex Disk';

    public function handle(YandexDiskService $service): void
    {
        $disk = Storage::disk('yandex');
        $files = $disk->files('catalog', true); // recursive

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $path) {
            $filename = basename($path);
            $dirname = dirname($path);

            // Определяем категорию из имени папки
            $categorySlug = basename($dirname);
            $category = CatalogCategory::where('slug', $categorySlug)->first();

            if (!$category) {
                $this->warn("  Category not found: {$categorySlug}");
                $bar->advance();
                continue;
            }

            // Проверяем, есть ли уже такой файл
            $existing = CatalogImage::where('title', pathinfo($filename, PATHINFO_FILENAME))
                ->where('category_id', $category->id)
                ->exists();

            if ($existing) {
                $bar->advance();
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("  Would add: {$filename} → {$category->name}");
                $bar->advance();
                continue;
            }

            // Скачиваем и создаём запись
            $tempPath = tempnam(sys_get_temp_dir(), 'yandex_');
            file_put_contents($tempPath, $disk->get($path));

            $image = CatalogImage::create([
                'title' => pathinfo($filename, PATHINFO_FILENAME),
                'category_id' => $category->id,
            ]);

            $image->addMedia($tempPath)
                ->toMediaCollection('catalog');

            unlink($tempPath);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Sync completed!');
    }
}
```

---

## 13. Миграция данных с WordPress

### 13.1. Общая стратегия

Миграция выполняется в два этапа:
1. **Экспорт** из WordPress БД в промежуточный JSON
2. **Импорт** в новый движок через Artisan-команду

### 13.2. Структура экспорта

```json
{
  "catalog": {
    "categories": [
      {"wp_term_id": 1, "slug": "abstr", "name": "Абстракция"}
    ],
    "colors": [
      {"wp_term_id": 2, "slug": "red", "name": "Красный", "hex": "#E74C3C"}
    ],
    "images": [
      {
        "wp_post_id": 123,
        "title": "Арт-1234",
        "category_id": 1,
        "colors": [2, 5],
        "image_url": "https://devsteklo.dev/wp-content/uploads/2024/01/image.jpg"
      }
    ]
  },
  "works": {
    "categories": [
      {"wp_term_id": 10, "slug": "skinali", "name": "Скинали"}
    ],
    "items": [
      {
        "wp_post_id": 456,
        "title": "Кухня в скандинавском стиле",
        "category_id": 10,
        "image_url": "https://devsteklo.dev/wp-content/uploads/2024/01/work.jpg"
      }
    ]
  }
}
```

### 13.3. Artisan-команда импорта

```php
// app/Console/Commands/ImportWordPress.php
class ImportWordPress extends Command
{
    protected $signature = 'artdecor:import-wp {file : Path to JSON export file}';
    protected $description = 'Import data from WordPress export JSON';

    public function handle(): void
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return;
        }

        $data = json_decode(file_get_contents($file), true);

        // Импорт категорий каталога
        $this->importCatalogCategories($data['catalog']['categories']);
        // Импорт цветов
        $this->importCatalogColors($data['catalog']['colors']);
        // Импорт изображений
        $this->importCatalogImages($data['catalog']['images']);
        // Импорт портфолио
        $this->importWorks($data['works']);

        $this->info('Import completed successfully!');
    }

    private function importCatalogImages(array $images): void
    {
        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        foreach ($images as $item) {
            try {
                $image = CatalogImage::create([
                    'title' => $item['title'],
                    'category_id' => $this->categoryMap[$item['category_id']] ?? null,
                    'is_active' => true,
                ]);

                if (!empty($item['colors'])) {
                    $image->colors()->attach(
                        array_map(fn($id) => $this->colorMap[$id] ?? null,
                        array_filter($item['colors']))
                    );
                }

                // Скачиваем и загружаем изображение
                if (!empty($item['image_url'])) {
                    $tempPath = tempnam(sys_get_temp_dir(), 'wp_');
                    file_put_contents($tempPath, file_get_contents($item['image_url']));
                    $image->addMedia($tempPath)->toMediaCollection('catalog');
                    unlink($tempPath);
                }
            } catch (\Exception $e) {
                $this->warn("  Failed to import {$item['title']}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
```

---

## 14. Кэширование

```php
// config/cache.php
return [
    'default' => env('CACHE_DRIVER', 'redis'),
    'prefix' => env('CACHE_PREFIX', 'artdecor'),
];

// config/queue.php
return [
    'default' => env('QUEUE_CONNECTION', 'redis'),
];
```

### 14.1. Уровни кэширования

| Уровень | Что кэшируется | TTL | Инвалидация |
|---------|---------------|-----|-------------|
| **Blade** | Фрагменты страниц (шапка, подвал) | 1 час | При изменении настроек |
| **Redis** | Списки категорий, цветов | 1 час | При CRUD в админке |
| **Redis** | Популярные запросы каталога | 30 мин | При изменении изображений |
| **nginx** | Полные страницы (public) | 5 мин | purge по событию |
| **Laravel** | Конфиг, маршруты, представления | ∞ (cache) | php artisan optimize |

### 14.2. Пример кэширования запросов каталога

```php
class CatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'catalog:' . md5(json_encode($request->only(['category', 'color', 'search', 'page'])));

        return Cache::remember($cacheKey, 1800, function () use ($request) {
            $images = CatalogImage::active()
                ->with(['category', 'colors', 'media'])
                ->filter($request->only(['category', 'color', 'search']))
                ->orderBy('sort_order')
                ->paginate(12);

            return response()->json($images);
        });
    }
}
```

### 14.3. Сброс кэша при изменениях

```php
// AppServiceProvider.php или Observer
class CatalogImageObserver
{
    public function saved(CatalogImage $image): void
    {
        Cache::tags(['catalog'])->flush();
    }

    public function deleted(CatalogImage $image): void
    {
        Cache::tags(['catalog'])->flush();
    }
}
```

---

## 15. Безопасность

### 15.1. CSRF-защита

- Laravel CSRF-токен встроен во все формы (Blade `@csrf`)
- API-запросы через `axios` автоматически отправляют `X-CSRF-TOKEN` из meta-тега
- Sanctum для SPA-подобного API (если понадобится)

### 15.2. Валидация

- Form Request для всех входящих данных
- Кастомные правила для телефона (`regex:/^[\+\d\s\-\(\)]+$/`)
- Экранирование вывода через Blade (`{{ }}`)

### 15.3. Защита БД

- Eloquent (подготовленные запросы) вместо raw SQL
- Пароли: bcrypt/Argon2
- Настройка CORS

### 15.4. Ограничение доступа

- Админ-панель: middleware `auth` + `role:admin|manager`
- Rate limiting для API (`throttle:60,1`)

### 15.5. Production-чеклист

- [ ] SMTP вместо `mail()` для всех писем
- [ ] HTTPS (Let's Encrypt / Yandex Certificate Manager)
- [ ] CSP-заголовки (Content Security Policy)
- [ ] Регулярные бэкапы БД + файлов (cron)
- [ ] Мониторинг (Yandex Monitoring / Sentry)
- [ ] Ограничение доступа к админке по IP (опционально)

---

## 16. Интеграции

### 16.1. Яндекс.Метрика

- ID счётчика: 95172838
- Передача целей: отправка форм, добавление в избранное
- Вставка через layout (см. раздел 6.1)

### 16.2. Jivosite

- ID виджета: YVm3vjP8Wd
- Асинхронная загрузка через `<script>` в footer

### 16.3. Яндекс.Карты

- API: JavaScript API (публичный)
- Страница контактов: `<div id="map">` + `ymaps3Map`

### 16.4. Почта

- SMTP через Yandex Mail для домена (или Mailgun)
- Отправка заказов на `info@skinali.moscow`
- Настройка в `.env`:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.yandex.ru
  MAIL_PORT=465
  MAIL_USERNAME=info@skinali.moscow
  MAIL_PASSWORD=your_password
  MAIL_ENCRYPTION=ssl
  MAIL_ADMIN_ADDRESS=info@skinali.moscow
  ```

---

## 17. SEO

### 17.1. Автоматическая генерация

```php
// routes/web.php
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [RobotsController::class, 'index']);
```

### 17.2. Sitemap

```php
class SitemapController extends Controller
{
    public function index(): Response
    {
        $images = CatalogImage::active()->get();
        $works = Work::all();
        $services = Service::all();

        return response()->view('seo.sitemap', compact('images', 'works', 'services'), 200)
            ->header('Content-Type', 'application/xml');
    }
}
```

```blade
{{-- resources/views/seo/sitemap.blade.php --}}
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc><priority>1.0</priority></url>
    <url><loc>{{ route('catalog') }}</loc><priority>0.9</priority></url>
    <url><loc>{{ route('primerka') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('works') }}</loc><priority>0.8</priority></url>
    @foreach($images as $image)
        <url>
            <loc>{{ route('catalog.show', [$image->category->slug, $image->id]) }}</loc>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
```

### 17.3. Мета-теги

- Title: `{{ $title ?? Setting::get('seo.default_title') }}`
- Description: `{{ $description ?? Setting::get('seo.default_description') }}`
- Open Graph: title, type, url, image
- Canonical URL: `<link rel="canonical" href="{{ url()->current() }}">`

### 17.4. ЧПУ

- `/izobrazheniya/{category}/{color?}/{slug?}` — товары
- `/nashi_raboti/{slug}` — работы
- `/uslugi/{slug}` — услуги
- 301 редиректы со старых WP-URL на новые

---

## 18. Тестирование

### 18.1. Unit-тесты

```bash
php artisan make:test Services/FavoritesServiceTest --unit
php artisan make:test Services/OrderServiceTest --unit
php artisan make:test Models/CatalogImageTest --unit
```

```php
class CatalogImageTest extends TestCase
{
    public function test_filter_by_category(): void
    {
        $category = CatalogCategory::factory()->create();
        $image = CatalogImage::factory()->create(['category_id' => $category->id]);

        $result = CatalogImage::filter(['category' => $category->id])->get();

        $this->assertCount(1, $result);
        $this->assertEquals($image->id, $result->first()->id);
    }

    public function test_filter_by_color(): void
    {
        $color = CatalogColor::factory()->create();
        $image = CatalogImage::factory()->create();
        $image->colors()->attach($color);

        $result = CatalogImage::filter(['color' => $color->id])->get();

        $this->assertCount(1, $result);
    }
}
```

### 18.2. Feature-тесты

```php
class CatalogApiTest extends TestCase
{
    public function test_catalog_api_returns_paginated_results(): void
    {
        CatalogImage::factory(25)->create();

        $response = $this->getJson('/api/catalog?page=1');

        $response->assertOk()
            ->assertJsonStructure(['data', 'current_page', 'last_page', 'total'])
            ->assertJsonCount(12, 'data');
    }
}
```

### 18.3. Нагрузочное тестирование

```bash
# Установка K6
# Сценарий: 100 одновременных пользователей, 5 мин
k6 run --vus 100 --duration 5m load-test.js
```

---

## 19. Деплой

### 19.1. Целевая инфраструктура (Yandex Cloud)

| Ресурс | Конфигурация |
|--------|-------------|
| **VM** | 2 vCPU, 4 ГБ RAM |
| **PostgreSQL** | Managed, 1 vCPU, 2 ГБ RAM |
| **Object Storage** | Для изображений + CDN |
| **Redis** | Managed или на VM |
| **NLB** | Балансировщик (опционально) |

### 19.2. Процесс деплоя

```bash
# 1. Pull последней версии
git pull origin main

# 2. Установка зависимостей
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 3. Кэширование
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Миграции
php artisan migrate --force

# 5. Симлинк на storage
php artisan storage:link

# 6. Запуск очередей
php artisan horizon

# 7. Перезагрузка PHP-FPM
sudo systemctl reload php8.3-fpm
```

### 19.3. Cron

```cron
* * * * * cd /var/www/artdecor && php artisan schedule:run >> /dev/null 2>&1
```

### 19.4. Мониторинг

```bash
composer require laravel/telescope --dev  # Dev-окружение
```

---

## 20. Критерии готовности

1. **Все публичные страницы** работают: главная, каталог, примерка, портфолио, услуги, контакты.
2. **Админ-панель** позволяет управлять всеми сущностями (CRUD).
3. **Каталог**: фильтрация по категории + цвету, infinite scroll, избранное.
4. **Примерка**: выбор изображения, смена цвета фасадов, артикул, избранное, заказ.
5. **Формы**: отправка, валидация, CSRF, email-уведомление, сохранение в БД.
6. **Миграция данных**: количество записей совпадает с исходным WP, изображения отображаются.
7. **Производительность**: Lighthouse Performance > 90, Accessibility > 90.
8. **Безопасность**: отсутствие критических уязвимостей.
9. **SEO**: sitemap.xml, robots.txt, мета-теги, ЧПУ, 301-редиректы.
10. **Интеграции**: Яндекс.Метрика, Jivosite, Яндекс.Карты, SMTP.
11. **Адаптивность**: корректное отображение на мобильных, планшетах, десктопах.
12. **Очереди**: генерация превью в фоне, отправка писем через очередь.
