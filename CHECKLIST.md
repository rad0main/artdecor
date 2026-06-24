# Чек-лист разработки ArtDecor

> Полный перечень задач по разработке проекта.  
> **Стек:** Laravel 11 + Filament v3 + PostgreSQL + Alpine.js + Tailwind CSS  
> **Статус:** ▢ — не начато, ☐ — в работе, ☑ — завершено

---

## Фаза 0: Инфраструктура и окружение

### 0.1. Локальное окружение
- [ ] Установить PHP 8.3 + Composer
- [ ] Установить PostgreSQL 15+
- [ ] Установить Redis
- [ ] Установить Node.js + npm
- [ ] Настроить Docker (Laravel Sail) — опционально

### 0.2. Создание проекта Laravel
- [ ] `composer create-project laravel/laravel:^11.0 artdecor --prefer-dist`
- [ ] Настроить `.env` (DB=pgsql, Redis, Mail)
- [ ] `php artisan key:generate`
- [ ] Настроить `config/database.php` для PostgreSQL
- [ ] Настроить `config/cache.php` (driver=redis)
- [ ] Настроить `config/queue.php` (connection=redis)

### 0.3. Установка зависимостей
- [ ] `composer require filament/filament:"^3.2"`
- [ ] `php artisan filament:install --panels`
- [ ] `composer require spatie/laravel-medialibrary:"^11.0"`
- [ ] `php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"`
- [ ] `composer require filament/spatie-laravel-media-library-plugin:"^3.2"`
- [ ] `composer require spatie/laravel-permission`
- [ ] `composer require laravel/sanctum`
- [ ] `composer require laravel/telescope --dev`
- [ ] `npm install -D tailwindcss postcss autoprefixer`
- [ ] `npm install alpinejs axios`
- [ ] `npx tailwindcss init -p`

### 0.4. Конфигурация Tailwind
- [ ] Настроить `tailwind.config.js` с кастомными цветами и шрифтами (см. DESIGN1.md §1.2)
- [ ] Добавить `@tailwind` директивы в `resources/css/app.css`
- [ ] Добавить CSS-переменные дизайн-системы в `app.css` (DESIGN1.md §1.1)

---

## Фаза 1: База данных и модели

### 1.1. Миграции (порядок важен!)
- [ ] `catalog_categories` — 11 категорий (INSTRUCTION.md §3.1)
- [ ] `catalog_colors` — 13 цветов с HEX
- [ ] `catalog_images` — с `foreignId('category_id')`
- [ ] `catalog_image_color` — pivot-таблица (image_id, color_id)
- [ ] `work_categories` — 9 категорий работ
- [ ] `works` — с `foreignId('category_id')`
- [ ] `services`
- [ ] `slides`
- [ ] `orders` — с JSON `article_ids`, статусами new/contacted/closed
- [ ] `favorites` — с `session_id` + `image_id` + unique constraint
- [ ] `settings` — key-value для контактов, интеграций, SEO

### 1.2. Сидеры (Seeders)
- [ ] `CatalogCategorySeeder` — 11 категорий: abstr, animals, architecture, cities, design, food, flowers, nature, sea, textures, waterfalls
- [ ] `CatalogColorSeeder` — 13 цветов с HEX-кодами
- [ ] `WorkCategorySeeder` — 9 категорий работ
- [ ] `SettingSeeder` — контакты, интеграции, SEO по умолчанию
- [ ] `AdminUserSeeder` — первый администратор

### 1.3. Eloquent-модели
- [ ] `CatalogImage` — связи: category (BelongsTo), colors (BelongsToMany), media, scopeFilter, scopeActive, Spatie Media Collections
- [ ] `CatalogCategory` — hasMany images
- [ ] `CatalogColor` — belongsToMany images
- [ ] `Work` — category (BelongsTo), media
- [ ] `WorkCategory` — hasMany works
- [ ] `Service`
- [ ] `Slide`
- [ ] `Order` — casts: article_ids (array)
- [ ] `Favorite` — image (BelongsTo), session_id index
- [ ] `Setting` — CRUD helper-методы: `get($key)`, `set($key, $value)`

### 1.4. Media Library конфигурация
- [ ] Настроить `config/medialibrary.php`
- [ ] Определить коллекции: `catalog`, `works`, `slides`, `services`
- [ ] Определить conversions: `thumb` (300×300 WebP), `preview` (800×600 WebP)
- [ ] Включить `queue_conversions_by_default = true`
- [ ] Настроить S3-совместимый диск (Yandex Object Storage)

---

## Фаза 2: Дизайн-система (CSS + Tailwind)

### 2.1. CSS-переменные и классы
- [ ] Определить CSS-переменные в `app.css` (DESIGN1.md §1.1):
  - `--k-color-primary: #E1323D`
  - `--k-color-primary-hover: #C82832`
  - `--k-color-secondary: #242E38`
  - `--k-color-bg-surface: #F9F9F9`
  - `--k-color-text-primary: #3C3D41`
  - `--k-color-text-secondary: #808285`
  - `--k-color-border: #EFEFEF`
  - `--k-color-accent: #6EC1E4`
- [ ] Создать класс `.btn-primary` (DESIGN1.md §4.1)
- [ ] Создать класс `.btn-secondary`
- [ ] Создать класс `.card`
- [ ] Настроить кастомный скроллбар (DESIGN1.md §4.8)

### 2.2. Шрифты
- [ ] Подключить Google Fonts: Montserrat (400,600,700) + PT Sans (400,700)
- [ ] Определить `font-heading` (Montserrat) и `font-body` (PT Sans) в CSS

### 2.3. Tailwind config
- [ ] Расширить `fontFamily`: heading, body
- [ ] Расширить `colors`: brand, surface, text, border

### 2.4. Анимации
- [ ] `@keyframes shimmer` для skeleton screen (DESIGN1.md §15.2)
- [ ] `@keyframes fadeIn` для появления карточек
- [ ] CSS-класс `.lazy-blur` для lazy loading (DESIGN1.md §15.3)

---

## Фаза 3: Filament — админ-панель

### 3.1. Настройка панели
- [ ] Настроить `app/Providers/Filament/AdminPanelProvider.php`
- [ ] Настроить брендинг (название, логотип, цвета)
- [ ] Настроить навигацию (группы: Каталог, Портфолио, Заказы, Настройки)

### 3.2. Ресурсы Filament

#### Изображения каталога (`CatalogImageResource`)
- [ ] Form: артикул (title), категория (select), цвета (multi-select), is_active (toggle), SpatieMediaLibraryFileUpload, sort_order
- [ ] Table: миниатюра, артикул, категория, цвета (badge), активно (toggle), дата
- [ ] Filters: по категории, по цвету
- [ ] Bulk actions: удаление, массовое изменение категории
- [ ] Reorderable: по sort_order
- [ ] Widget: статистика на дашборде (кол-во изображений)

#### Категории каталога (`CatalogCategoryResource`)
- [ ] Form: название, slug (auto), sort_order
- [ ] Table: название, slug, сортировка, кол-во изображений

#### Цвета каталога (`CatalogColorResource`)
- [ ] Form: название, slug (auto), hex (color picker), sort_order
- [ ] Table: цвет (превью), название, hex, slug

#### Работы (`WorkResource`)
- [ ] Form: название, категория (select), описание (rich text), is_featured (toggle), SpatieMediaLibraryFileUpload
- [ ] Table: миниатюра, название, категория, featured, дата

#### Категории работ (`WorkCategoryResource`)
- [ ] Form: название, slug
- [ ] Table: название, кол-во работ

#### Услуги (`ServiceResource`)
- [ ] Form: название, описание, иконка, sort_order
- [ ] Table: название, sort_order

#### Слайды (`SlideResource`)
- [ ] Form: изображение (SpatieMediaLibraryFileUpload), заголовок, подзаголовок, ссылка, текст кнопки, is_active, sort_order
- [ ] Table: превью, заголовок, активно

#### Заказы (`OrderResource`)
- [ ] Form: имя, телефон, сообщение, источник (badge), article_ids (JSON), статус (select: new/contacted/closed), комментарий менеджера
- [ ] Table: ID, имя, телефон, источник, статус (colored badge), дата
- [ ] Filters: по статусу, по источнику, по дате
- [ ] Actions: изменить статус, экспорт в CSV
- [ ] Widget: график заказов по дням

### 3.3. Кастомные страницы
- [ ] `Settings` (Filament\Pages\Page) — форма настроек (INSTRUCTION.md §4.4):
  - Контакты: телефон, email, адрес, часы работы
  - Интеграции: Яндекс.Метрика ID, Jivosite ID
  - SEO: дефолтные title и description
- [ ] Dashboard — виджеты: кол-во заказов (новые/все), кол-во изображений, последние заказы

### 3.4. Роли и разрешения
- [ ] Настроить Spatie Permission
- [ ] Создать роли: admin, manager
- [ ] Настроить middleware `auth` + `role` на ресурсах
- [ ] Создать `UserResource` для управления пользователями

---

## Фаза 4: Маршруты и контроллеры (публичная часть)

### 4.1. Маршруты (`routes/web.php`)
- [ ] `GET /` → HomeController@index
- [ ] `GET /izobrazheniya` → CatalogController@index
- [ ] `GET /izobrazheniya/{category}/{color?}/{slug?}` → CatalogController@show
- [ ] `GET /primerka` → PrimerkaController@index
- [ ] `GET /nashi_raboti` → WorkController@index
- [ ] `GET /uslugi` → ServiceController@index
- [ ] `GET /contacts` → ContactController@index
- [ ] `GET /sitemap.xml` → SitemapController@index
- [ ] `GET /robots.txt` → RobotsController@index

### 4.2. API-маршруты (`routes/api.php`)
- [ ] `GET /api/catalog` — пагинированный список с фильтрацией
- [ ] `GET /api/catalog/categories` — список категорий
- [ ] `GET /api/catalog/colors` — список цветов
- [ ] `GET /api/works` — список работ с фильтрацией
- [ ] `GET /api/favorites?session_id=` — избранное пользователя
- [ ] `POST /api/favorites` — добавить в избранное
- [ ] `DELETE /api/favorites/{imageId}` — удалить из избранного
- [ ] `POST /api/order` — создание заказа

### 4.3. Контроллеры
- [ ] `HomeController` — загружает слайды, последние работы, категории
- [ ] `CatalogController` (web) — загружает категории, цвета, рендерит Blade
- [ ] `CatalogController` (api) — paginate + filter + transform с Media URLs
- [ ] `PrimerkaController` — загружает категории, цвета, рендерит страницу
- [ ] `WorkController` (web) — рендерит страницу портфолио
- [ ] `WorkController` (api) — paginate + filter + transform
- [ ] `ServiceController` — список услуг
- [ ] `ContactController` — контакты + форма
- [ ] `FavoriteController` (api) — CRUD избранного
- [ ] `OrderController` (api) — создание заказа с валидацией

### 4.4. Form Requests
- [ ] `OrderRequest` — валидация: name, phone (regex), message, source (enum), article_ids (array), facade_top/bottom_color

### 4.5. Middleware
- [ ] `SessionIdMiddleware` — опционально, для привязки session_id к гостям

---

## Фаза 5: Blade-шаблоны

### 5.1. Layout (`resources/views/layouts/app.blade.php`)
- [ ] DOCTYPE + html lang="ru" (INSTRUCTION.md §6.1)
- [ ] Meta: charset, viewport, csrf-token
- [ ] SEO meta: title, description, Open Graph
- [ ] Google Fonts (Montserrat + PT Sans)
- [ ] @vite для CSS/JS
- [ ] Структура: header, slot (main), footer
- [ ] Jivosite widget (условно, из настроек)
- [ ] Яндекс.Метрика (условно, из настроек)

### 5.2. Компоненты

#### Header (`resources/views/components/header.blade.php`)
- [ ] Верхняя полоса: «Заказать звонок» + телефон (INSTRUCTION.md §6.3, DESIGN1.md §5)
- [ ] Основная навигация: логотип слева/центр, меню слева и справа
- [ ] Мобильный гамбургер (lg:hidden)
- [ ] Sticky-позиционирование (position: sticky)

#### Footer (`resources/views/components/footer.blade.php`)
- [ ] 3 колонки: Контакты, Разделы, Мы в сети (DESIGN1.md §6)
- [ ] Фон: #242E38, текст: белый
- [ ] Копирайт
- [ ] Jivosite widget (правый нижний угол)

#### Catalog Card (`resources/views/components/catalog-card.blade.php`)
- [ ] Изображение (lazy loading, object-fit cover)
- [ ] Артикул
- [ ] Кнопка избранного (♡/♥) (DESIGN1.md §4.2)

#### Work Card (`resources/views/components/work-card.blade.php`)
- [ ] Изображение + hover-оверлей затемнения
- [ ] Название работы (появляется снизу при hover) (DESIGN1.md §4.3)

#### Filters (`resources/views/components/filters.blade.php`)
- [ ] Выпадающий список категорий
- [ ] Выпадающий список цветов (или swatches)
- [ ] Поле поиска с debounce
- [ ] Активные фильтры (tags с кнопкой ×)
- [ ] Кнопка «Сбросить»

#### Modal (`resources/views/components/modal.blade.php`)
- [ ] Overlay (rgba(0,0,0,0.5))
- [ ] Центрированный контейнер (max-width: 480px)
- [ ] Кнопка закрытия (×)
- [ ] Анимация: scale(0.95→1) + fade (DESIGN1.md §13.1)
- [ ] Закрытие: ×, клик вне, Escape

#### Order Form (`resources/views/components/order-form.blade.php`)
- [ ] Поля: имя, телефон, сообщение
- [ ] Скрытое поле article
- [ ] CSRF-токен (через axios)
- [ ] Валидация на фронте

### 5.3. Страницы

#### Главная (`resources/views/pages/home.blade.php`)
- [ ] Hero-слайдер (DESIGN1.md §7.2):
  - Карусель с автоплеем 5 сек
  - Точки пагинации
  - Стрелки навигации
  - Адаптивная высота (400-500px → 250-300px)
- [ ] Категории продукции (иконки + подписи)
- [ ] Блок «О компании / Преимущества»
- [ ] Последние работы (4 карточки)
- [ ] Форма обратной связи

#### Каталог (`resources/views/pages/catalog.blade.php`)
- [ ] Заголовок: «Каталог изображений»
- [ ] Фильтры (DESIGN1.md §8.3, INSTRUCTION.md §5.3)
- [ ] Сетка карточек (grid: 1→2→3→4 колонок)
- [ ] Infinite scroll / кнопка «Загрузить ещё»
- [ ] Alpine.js: catalogGrid (фильтрация + подгрузка)

#### Примерка (`resources/views/pages/primerka.blade.php`)
- [ ] Двухколоночный макет (левая панель + схема)
- [ ] Список категорий (слева)
- [ ] Палитра цветов верхних фасадов
- [ ] Палитра цветов нижних фасадов
- [ ] SVG-схема кухни (DESIGN1.md §9.2, INSTRUCTION.md §7.1):
  - Верхние фасады (rect)
  - Фартук с изображением (image, preserveAspectRatio)
  - Бордюр-полка
  - Нижние фасады (rect)
  - Ручки (circle)
- [ ] Артикул выбранного изображения
- [ ] Кнопки: «В избранное», «Заказать»
- [ ] Миниатюры изображений (по категории)
- [ ] Блок «Моё избранное» (горизонтальный скролл)
- [ ] Модальное окно заказа
- [ ] Alpine.js: primerka (INSTRUCTION.md §7.2)

#### Портфолио (`resources/views/pages/works.blade.php`)
- [ ] Заголовок: «Наши работы»
- [ ] Фильтры по категориям (кнопки) + по цветам (DESIGN1.md §10.2)
- [ ] Masonry/grid сетка карточек
- [ ] Hover-эффект: затемнение + название (DESIGN1.md §10.3)
- [ ] Пагинация / infinite scroll
- [ ] Fancybox / GLightbox для просмотра полноразмерных

#### Услуги (`resources/views/pages/services.blade.php`)
- [ ] Заголовок: «Наши услуги»
- [ ] Сетка карточек (3→2→1 колонки)
- [ ] Иконка + название + краткое описание (DESIGN1.md §11)

#### Контакты (`resources/views/pages/contacts.blade.php`)
- [ ] Двухколоночный макет: контактная информация + карта
- [ ] Телефон, email, адрес, часы работы
- [ ] Яндекс.Карты (JavaScript API)
- [ ] Форма обратной связи (поля: имя, телефон, email, сообщение)
- [ ] Иконки соцсетей (DESIGN1.md §12)

---

## Фаза 6: Alpine.js — интерактивность

### 6.1. Основной JS (`resources/js/app.js`)
- [ ] Импорт Alpine + axios
- [ ] Компонент `catalogGrid` (INSTRUCTION.md §6.4):
  - состояние: images[], page, hasMore, loading, filters {}
  - init(): загрузка первой страницы, подписка на filter-change
  - loadImages(): GET /api/catalog, append к images
  - loadMore(): пагинация
- [ ] Компонент `favorites`:
  - состояние: items[], sessionId
  - getSessionId(): localStorage
  - load(): GET /api/favorites
  - toggle(imageId): POST/DELETE /api/favorites
  - isFavorite(imageId): boolean
- [ ] Компонент `infiniteScroll`:
  - Intersection Observer на кнопке «Загрузить ещё»

### 6.2. Компонент примерки (`resources/js/primerka.js`)
- [ ] Компонент `primerka` (INSTRUCTION.md §7.2):
  - init(): загрузка категорий, цветов, первого набора изображений
  - selectCategory(catId): сброс + загрузка изображений
  - loadImages(): GET /api/catalog?category=&limit=50
  - selectTopColor(colorId): обновление hex верхних фасадов
  - selectBottomColor(colorId): обновление hex нижних фасадов
  - selectImage(img): установка выбранного изображения
  - addToFavorites(): dispatch event
  - openOrderForm(): заполнение артикула, открытие модалки
  - submitOrder(): POST /api/order + toast

### 6.3. Компонент модального окна
- [ ] `x-data="modal()"`:
  - open(name): показать modal
  - close(): скрыть
  - clickOutside(): close()
  - keydown Escape: close()

### 6.4. Компонент слайдера
- [ ] Автоплей (5 сек)
- [ ] Точки пагинации
- [ ] Стрелки «влево/вправо»
- [ ] Touch/swipe на мобильных

---

## Фаза 7: Сервисы (Business Logic)

### 7.1. FavoritesService
- [ ] `getFavorites(sessionId)` — возвращает коллекцию изображений с Media URLs
- [ ] `toggle(imageId, sessionId)` — добавить/удалить

### 7.2. OrderService
- [ ] `create(array $data)` — создание заказа (INSTRUCTION.md §10.1)
- [ ] Валидация phone (regex)
- [ ] Сохранение article_ids как JSON

### 7.3. PrimerkaService (опционально)
- [ ] Логика подбора изображений по категории
- [ ] Кэширование списков

### 7.4. ImageService
- [ ] Генерация WebP превью (thumb: 300×300, preview: 800×600)
- [ ] Определение размеров (width, height)
- [ ] Хеширование файлов (SHA-256) для дедупликации

---

## Фаза 8: Почта и уведомления

### 8.1. Mailable
- [ ] `OrderCreatedMail` (INSTRUCTION.md §10.2):
  - Тема: «Новый заказ #ID с сайта ArtDecor»
  - Markdown-шаблон: имя, телефон, сообщение, артикулы, источник
- [ ] Очередь: `ShouldQueue`

### 8.2. Mail config
- [ ] Настройка `.env`: SMTP Yandex/Google/Mailgun
- [ ] `config/mail.php`: admin_address

### 8.3. Уведомления в админке
- [ ] Notification при новом заказе (Filament Notification)
- [ ] Badge на иконке заказов в навигации (кол-во новых)

---

## Фаза 9: Интеграции

### 9.1. Яндекс.Метрика
- [ ] Вставка счётчика в layout (INSTRUCTION.md §6.1)
- [ ] ID из settings (integrations.yandex_metrika_id)
- [ ] Настройка целей: отправка формы, добавление в избранное

### 9.2. Jivosite
- [ ] Вставка script-виджета (INSTRUCTION.md §16.2)
- [ ] ID из settings (integrations.jivosite_id)

### 9.3. Яндекс.Карты
- [ ] JavaScript API на странице контактов
- [ ] Маркер офиса
- [ ] Адаптивный размер карты

### 9.4. SMTP
- [ ] Настройка почтового сервера (INSTRUCTION.md §16.4)
- [ ] Тестовая отправка письма

---

## Фаза 10: Обработка изображений

### 10.1. Spatie Media Library
- [ ] Настройка `filesystems.disks.yandex-cloud` (S3)
- [ ] Определение коллекций и conversions
- [ ] Загрузка через админку (SpatieMediaLibraryFileUpload)
- [ ] Проверка: thumb (300×300 WebP), preview (800×600 WebP)

### 10.2. Artisan-команды
- [ ] `GenerateThumbs` — пакетная генерация/перегенерация превью (INSTRUCTION.md §11.4)
- [ ] `CleanUnusedMedia` — удаление медиа без связанных моделей

### 10.3. Lazy Loading
- [ ] `loading="lazy"` на всех изображениях
- [ ] Intersection Observer для подгрузки
- [ ] Placeholder (skeleton или blur-up)

---

## Фаза 11: Синхронизация с Яндекс.Диском

### 11.1. Настройка диска
- [ ] `config/filesystems.php` — disk `yandex` (API Яндекс.Диска)
- [ ] OAuth-токен для доступа

### 11.2. Artisan-команда (`SyncYandexDisk`)
- [ ] Чтение файлов из папок Яндекс.Диска (INSTRUCTION.md §12)
- [ ] Определение категории по имени папки
- [ ] Проверка существования (по артикулу + категории)
- [ ] Скачивание → создание CatalogImage → addMedia
- [ ] Dry-run режим (`--dry-run`)
- [ ] Progress bar

### 11.3. Cron
- [ ] `schedule:command('artdecor:sync-yandex')->hourly()`

---

## Фаза 12: Миграция данных с WordPress

### 12.1. Экспорт из WordPress
- [ ] Написать скрипт экспорта WP→JSON (INSTRUCTION.md §13.2):
  - catalog_categories → wp_term_taxonomy('category_catalog')
  - catalog_colors → wp_term_taxonomy('color')
  - catalog_images → wp_posts(post_type='catalog') + thumbnail
  - catalog_image_colors → wp_term_relationships
  - works → wp_posts(post_type='work')
  - work_categories → wp_term_taxonomy('works_category')
  - favorites → wp_postmeta('wpfp_favorites')

### 12.2. Artisan-команда (`ImportWordPress`)
- [ ] Чтение JSON-файла (INSTRUCTION.md §13.3)
- [ ] Маппинг старых ID на новые
- [ ] Импорт категорий и цветов
- [ ] Импорт изображений (скачивание + Media Library)
- [ ] Импорт портфолио
- [ ] Импорт избранного
- [ ] Progress bar + отчёт об ошибках

### 12.3. Проверка целостности
- [ ] Сравнение кол-ва записей (WP vs new)
- [ ] Проверка наличия изображений (по URL)
- [ ] Тест: все страницы открываются с реальными данными

---

## Фаза 13: Кэширование

### 13.1. Конфигурация
- [ ] `CACHE_DRIVER=redis` в .env
- [ ] `QUEUE_CONNECTION=redis` в .env

### 13.2. Кэширование запросов
- [ ] Список категорий (Cache::remember, TTL 3600)
- [ ] Список цветов (Cache::remember, TTL 3600)
- [ ] API каталога (Cache::remember, TTL 1800, INSTRUCTION.md §14.2)
- [ ] Настройки сайта (Cache::remember, TTL 3600)

### 13.3. Инвалидация кэша
- [ ] Observer на CatalogImage → Cache::tags(['catalog'])->flush() (INSTRUCTION.md §14.3)
- [ ] Observer на CatalogCategory → flush
- [ ] Observer на CatalogColor → flush
- [ ] Observer на Setting → flush

### 13.4. Laravel optimization
- [ ] `config:cache`
- [ ] `route:cache`
- [ ] `view:cache`

### 13.5. nginx fastcgi_cache (на сервере)
- [ ] Настройка кэширования публичных страниц (TTL 5 мин)
- [ ] Purge при обновлении контента

---

## Фаза 14: SEO

### 14.1. Мета-теги
- [ ] title (уникальный для каждой страницы)
- [ ] description (из настроек или страницы)
- [ ] Open Graph: title, type (website), url, image
- [ ] canonical URL: `<link rel="canonical" href="{{ url()->current() }}">`

### 14.2. Sitemap
- [ ] `SitemapController@index` — XML-генерация (INSTRUCTION.md §17.2)
- [ ] Включить: главная, каталог, все изображения, работы, услуги
- [ ] Priority: 1.0 (главная) → 0.9 (каталог) → 0.8 (работы, услуги) → 0.6 (детальные)

### 14.3. Robots.txt
- [ ] `RobotsController@index`
- [ ] Allow: все страницы
- [ ] Disallow: /admin, /api
- [ ] Sitemap: /sitemap.xml

### 14.4. ЧПУ (Human-friendly URLs)
- [ ] `/izobrazheniya/{category}/{color?}/{slug?}`
- [ ] `/nashi_raboti/{slug}`
- [ ] `/uslugi/{slug}`

### 14.5. 301-редиректы (миграция)
- [ ] /catalog/{id} → /izobrazheniya/{category}/{slug}
- [ ] /portfolio/{id} → /nashi_raboti/{slug}
- [ */?page_id=* → соответствующие новые URL

---

## Фаза 15: Безопасность

### 15.1. Встроенные механизмы
- [ ] CSRF-защита (Laravel + meta csrf-token + axios)
- [ ] Валидация через Form Request
- [ ] Eloquent (подготовленные запросы)
- [ ] bcrypt/Argon2 для паролей

### 15.2. API
- [ ] Rate limiting: `throttle:60,1` на публичные API
- [ ] Rate limiting: `throttle:10,1` на формы (POST /api/order)
- [ ] CORS (если нужен)

### 15.3. Админ-панель
- [ ] Middleware `auth` на всех страницах Filament
- [ ] Роли: admin (полный доступ), manager (ограниченный)
- [ ] Защита от брутфорса (Login throttle)

### 15.4. Production
- [ ] HTTPS (Let's Encrypt / Certificate Manager)
- [ ] CSP-заголовки (Content-Security-Policy)
- [ ] X-Frame-Options: DENY
- [ ] X-Content-Type-Options: nosniff
- [ ] Бэкапы: ежедневный dump БД + rsync файлов
- [ ] Мониторинг (Sentry / Telescope — dev)

---

## Фаза 16: Тестирование

### 16.1. Unit-тесты
- [ ] `CatalogImageTest` — scopeFilter по категории, по цвету, комбинированный
- [ ] `FavoritesServiceTest` — добавление, удаление, дубли
- [ ] `OrderServiceTest` — создание заказа, валидация телефона

### 16.2. Feature-тесты
- [ ] `CatalogApiTest` — пагинация, фильтрация, пустые результаты
- [ ] `OrderApiTest` — успешный заказ, невалидные данные, CSRF
- [ ] `FavoritesApiTest` — CRUD с session_id
- [ ] `PageRenderTest` — все страницы открываются (HTTP 200)

### 16.3. Frontend-тесты (если нужно)
- [ ] Alpine.js: catalogGrid, primerka, favorites
- [ ] Адаптив: тест на мобильных размерах

### 16.4. Нагрузочное тестирование
- [ ] K6: 100 concurrent, 5 min
- [ ] Основные сценарии: просмотр каталога, фильтрация, примерка, заказ

### 16.5. Дизайн-ревью (DESIGN1.md Appendix)
- [ ] Цвета соответствуют палитре (Primary: #E1323D, Secondary: #242E38)
- [ ] Шрифты: Montserrat (заголовки) + PT Sans (текст)
- [ ] Все кнопки используют btn-primary/btn-secondary
- [ ] Карточки каталога: изображение + артикул + сердечко
- [ ] Карточки портфолио: hover-эффект с overlay
- [ ] Header: липкий, верхняя полоса + основное меню
- [ ] Footer: 3 колонки, #242E38 фон
- [ ] Фильтры: категории (select) + цвета (swatches) + поиск
- [ ] Примерка: SVG-схема кухни (3 области)
- [ ] Модальные окна: overlay + центрирование + Escape
- [ ] Адаптив: мобильная версия
- [ ] Lazy loading изображений

---

## Фаза 17: Деплой

### 17.1. Production-окружение (Yandex Cloud)
- [ ] Создать VM (2 vCPU, 4 ГБ RAM)
- [ ] Установить nginx + PHP 8.3-FPM
- [ ] Установить PostgreSQL 15
- [ ] Установить Redis
- [ ] Настроить Yandex Object Storage + CDN
- [ ] Настроить SSL (Let's Encrypt / Certificate Manager)

### 17.2. Развёртывание
- [ ] `git pull origin main`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `npm ci && npm run build`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `php artisan horizon`
- [ ] `sudo systemctl reload php8.3-fpm`
- [ ] Настроить supervisor для horizon

### 17.3. Cron
- [ ] `schedule:run` каждую минуту
- [ ] `artdecor:sync-yandex` — каждый час

### 17.4. Мониторинг
- [ ] Настроить Yandex Monitoring (CPU, RAM, диск)
- [ ] Настроить алерты
- [ ] Включить Telescope на staging (отключить на production)

### 17.5. Запуск
- [ ] Переключить document root на public/
- [ ] Настроить 301-редиректы со старого WP
- [ ] Проверить логи (nginx, Laravel)
- [ ] Проверить Яндекс.Метрику, Jivosite
- [ ] Проверить отправку почты

---

## Итого: ~200 пунктов

| Фаза | Пунктов | Приоритет |
|------|---------|-----------|
| 0. Инфраструктура | 18 | 🔴 Высокий |
| 1. БД и модели | 18 | 🔴 Высокий |
| 2. Дизайн-система | 11 | 🔴 Высокий |
| 3. Filament админка | 23 | 🔴 Высокий |
| 4. Маршруты и контроллеры | 16 | 🔴 Высокий |
| 5. Blade-шаблоны | 43 | 🔴 Высокий |
| 6. Alpine.js | 14 | 🔴 Высокий |
| 7. Сервисы | 6 | 🟡 Средний |
| 8. Почта | 5 | 🟡 Средний |
| 9. Интеграции | 7 | 🟡 Средний |
| 10. Обработка изображений | 5 | 🔴 Высокий |
| 11. Синхронизация Я.Диск | 5 | 🟡 Средний |
| 12. Миграция WordPress | 7 | 🔴 Высокий |
| 13. Кэширование | 8 | 🟡 Средний |
| 14. SEO | 8 | 🟡 Средний |
| 15. Безопасность | 10 | 🔴 Высокий |
| 16. Тестирование | 10 | 🟡 Средний |
| 17. Деплой | 13 | 🔴 Высокий |

**Оценка:** ~25 рабочих дней (один разработчик)
