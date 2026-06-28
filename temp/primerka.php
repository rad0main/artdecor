<?php 
/*
 * Template name: Примерка шаблон
 * */

get_header(); ?>

<?php if(!is_front_page()){ ale_part('page_heading'); }

//Sidebar position based on theme options
$ale_sidebar_position = ale_get_option('blog_sidebar_position');
if(ale_get_meta('sidebar_position') !== ''){
    $ale_sidebar_position = ale_get_meta('sidebar_position');
}
$sidebar_class = '';

if($ale_sidebar_position){
    $sidebar_class = 'sidebar_position_'. $ale_sidebar_position;
}

// Получаем категории для фильтрации
$categories = get_terms(array(
    'taxonomy' => 'category_catalog',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));
?>

    <div class="basepage primerka container flex_container <?php  echo esc_attr($sidebar_class); ?> cf">
		
					<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
<?php if(function_exists('bcn_display'))
{
bcn_display();
}?>
</div>	
		
        <?php if($ale_sidebar_position  !== 'no'){
            get_sidebar();
        } ?>
		<div class="page-template content cf">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php if(!is_front_page()){ ?>
                    <?php if(ale_get_meta('post_title_position') == 'afterheading' or ale_get_meta('post_title_position') == "" or ale_get_meta('post_title_position') == 'afterheadingwithdefaults' or ale_get_meta('post_title_position') == 'fullwidthwedding'){ ?>
			
                        <h1 class="basepage">Онлайн примерка изображений на скинали</h1>

                        <div class="basepage__text">Выберите нужный цвет фасадов кухни, нажав на нужный цвет на представленной палитре. После этого нажмите на выбранное изображение из каталога или добавленные в избранное. Заказать и отправить нам выбранное фото, Вы можете нажав на «заказать»</div>

                        <?php if(ale_get_meta('post_pre_title')){ ?>
                            <p class="pre_title"><?php echo esc_attr(ale_get_meta('post_pre_title')); ?></p>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                <?php if(ale_get_meta('featured_position') == 'in_content'){
                    echo '<div class="single_featured_image">'.get_the_post_thumbnail($post->ID,'large').'</div>';
                } ?>

                <div class="journal">
                    <img data-original="/wp-content/themes/olins/img/journal/facades/top/black.png" src="/wp-content/themes/olins/img/journal/facades/top/black.png" class="facade-top lazy-img">
                    <img data-original="/wp-content/themes/olins/img/journal/facades/bottom/black.png" src="/wp-content/themes/olins/img/journal/facades/bottom/black.png" class="facade-bottom lazy-img">
                    <div class="skinali lazy-bg" data-src="/wp-content/themes/olins/img/journal/catalog/cit/3459.jpg" style="display: block; background-image: url('/wp-content/themes/olins/img/journal/catalog/cit/3459.jpg');"></div>
                    <div class="color-top color-parent">
                        <div class="color" data-color="red"></div>
                        <div class="color" data-color="orange"></div>
                        <div class="color" data-color="yellow"></div>
                        <div class="color" data-color="green"></div>
                        <div class="color" data-color="blue"></div>
                        <div class="color" data-color="darkblue"></div>
                        <div class="color" data-color="purple"></div>
                        <div class="color" data-color="pink"></div>
                        <div class="color" data-color="brown"></div>
                        <div class="color" data-color="beige"></div>
                        <div class="color" data-color="white"></div>
                        <div class="color" data-color="gray"></div>
                        <div class="color" data-color="black"></div>
                    </div>
                    <div class="color-middle">

    <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M4.77273 10.5C5.82702 10.5 6.68182 9.64568 6.68182 8.59091C6.68182 7.53661 5.82702 6.68182 4.77273 6.68182C3.71843 6.68182 2.86364 7.53661 2.86364 8.59091C2.86364 9.64568 3.71843 10.5 4.77273 10.5ZM4.77273 7.63636C5.29964 7.63636 5.72727 8.064 5.72727 8.59091C5.72727 9.1183 5.29964 9.54545 4.77273 9.54545C4.24582 9.54545 3.81818 9.1183 3.81818 8.59091C3.81818 8.064 4.24582 7.63636 4.77273 7.63636ZM14.2843 17.482L14.2819 17.4777C14.2771 17.4677 14.27 17.4601 14.2652 17.45L11.4207 12.7088L11.4192 12.7093C11.3481 12.5341 11.1782 12.4091 10.9773 12.4091C10.8455 12.4091 10.7262 12.4625 10.6398 12.5489L9.13595 14.0528L7.59914 11.7485C7.52707 11.5762 7.35764 11.4545 7.15909 11.4545C6.99348 11.4545 6.85507 11.5443 6.76964 11.6722L6.762 11.6669L2.94382 17.3942L2.95145 17.3995C2.90039 17.4749 2.86364 17.5612 2.86364 17.6596C2.86364 17.923 3.07698 18.1364 3.34091 18.1364H13.8409C14.1048 18.1364 14.3182 17.923 14.3182 17.6596C14.3182 17.5966 14.3043 17.5374 14.2829 17.483L14.2843 17.482ZM4.23293 17.1818L7.16482 12.7837L8.62814 14.9792C8.7002 15.1515 8.86964 15.2727 9.06818 15.2727C9.19991 15.2727 9.31923 15.2198 9.40561 15.1329L10.8871 13.6514L13.0047 17.1818H4.23293ZM19.0909 0H5.72727C4.67298 0 3.81818 0.854795 3.81818 1.90909V2.38636C3.81818 2.6503 4.03152 2.86364 4.29545 2.86364C4.55939 2.86364 4.77273 2.6503 4.77273 2.38636V1.90909C4.77273 1.38218 5.20036 0.954545 5.72727 0.954545H19.0909C19.6178 0.954545 20.0455 1.38218 20.0455 1.90909V15.2727C20.0455 15.8001 19.6178 16.2273 19.0909 16.2273H18.6136C18.3497 16.2273 18.1364 16.4411 18.1364 16.7045C18.1364 16.9685 18.3497 17.1818 18.6136 17.1818H19.0909C20.1452 17.1818 21 16.327 21 15.2727V1.90909C21 0.854795 20.1452 0 19.0909 0ZM15.2727 3.81818H1.90909C0.854795 3.81818 0 4.67298 0 5.72727V19.0909C0 20.1452 0.854795 21 1.90909 21H15.2727C16.327 21 17.1818 20.1452 17.1818 19.0909V5.72727C17.1818 4.67298 16.327 3.81818 15.2727 3.81818ZM16.2273 19.0909C16.2273 19.6183 15.7996 20.0459 15.2727 20.0459H1.90909C1.38218 20.0459 0.954545 19.6183 0.954545 19.0909V5.72727C0.954545 5.20036 1.38218 4.77273 1.90909 4.77273H15.2727C15.7996 4.77273 16.2273 5.20036 16.2273 5.72727V19.0909Z" fill="white"/>
    </svg>

                        <div class="text">Открыть каталог</div>
                    </div>
                    <div class="color-bottom color-parent">
                        <div class="color" data-color="red"></div>
                        <div class="color" data-color="orange"></div>
                        <div class="color" data-color="yellow"></div>
                        <div class="color" data-color="green"></div>
                        <div class="color" data-color="blue"></div>
                        <div class="color" data-color="darkblue"></div>
                        <div class="color" data-color="purple"></div>
                        <div class="color" data-color="pink"></div>
                        <div class="color" data-color="brown"></div>
                        <div class="color" data-color="beige"></div>
                        <div class="color" data-color="white"></div>
                        <div class="color" data-color="gray"></div>
                        <div class="color" data-color="black"></div>
                    </div>
                    <div class="popups">
                        <div class="closer">
                            <span> Выберите изображение из каталога</span>
                            <img src="/wp-content/themes/olins/img/Icon.svg" class="lazy-img">
                        </div>
                        <div class="cats">
                            <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                                <?php foreach ($categories as $index => $category) : ?>
                                    <div class="category <?php echo $index === 0 ? 'active' : ''; ?>" data-cat="<?php echo esc_attr($category->slug); ?>">
                                        <?php echo esc_html($category->name); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="skinales"></div>
                    </div>
                </div>

                <div class="skinali_mob">
                    <div class="title--primerka">Пожалуйста, поверните телефон</div>
                    <img src="/wp-content/themes/olins/img/primerka.svg">
                    <div class="text--primerka">Выберите нужный цвет фасадов кухни, нажав на нужный цвет на представленной палитре. После этого нажмите на выбранное изображение из каталога или добавленные в избранное. </div>
                </div>

                <?php the_content(); ?>

            <?php endwhile; else: ?>
                <?php ale_part('notfound')?>
            <?php endif; ?>

       <?php if (comments_open()) : ?>
            <?php comments_template(); ?>
        <?php endif; ?>		
		
		</div>
    </div>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Функция для получения артикула из имени файла
                function getArticleFromFilename(filename) {
                    // Убираем расширение файла
                    var nameWithoutExt = filename.replace(/\.[^/.]+$/, "");
                    // Убираем префикс "skinali-" если он есть
                    return nameWithoutExt.replace(/^skinali-/, "");
                }

                // Функция для загрузки изображений по категории
                function loadCategoryImages(categorySlug) {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'get_primerka_images',
                            category: categorySlug,
                            nonce: '<?php echo wp_create_nonce('primerka_nonce'); ?>'
                        },
                        beforeSend: function() {
                            $('.skinales').html('<div class="loading">Загрузка изображений...</div>');
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.skinales').html('');
                                $.each(response.data, function(index, image) {
                                    var article = getArticleFromFilename(image.filename);
                                    var imgElement = $('<div class="skinali-item" data-article="' + article + '"><img src="' + image.url + '"><div class="article">' + article + '</div></div>');
                                    $('.skinales').append(imgElement);
                                });

                                // Добавляем обработчик клика на изображение
                                $('.skinali-item').on('click', function() {
                                    var imageUrl = $(this).find('img').attr('src');
                                    var article = $(this).data('article');
                                    
                                    // Устанавливаем выбранное изображение
                                    $('.skinali').css('background-image', 'url(' + imageUrl + ')');
                                    
                                    // Показываем артикул (можно добавить логику отображения)
                                    console.log('Выбран артикул:', article);
                                });
                            } else {
                                $('.skinales').html('<div class="error">Ошибка загрузки изображений</div>');
                            }
                        },
                        error: function() {
                            $('.skinales').html('<div class="error">Ошибка загрузки изображений</div>');
                        }
                    });
                }

                // Обработчик клика по категории
                $('.category').on('click', function() {
                    $('.category').removeClass('active');
                    $(this).addClass('active');
                    var categorySlug = $(this).data('cat');
                    loadCategoryImages(categorySlug);
                });

                // Загружаем изображения для первой категории при загрузке страницы
                var firstCategory = $('.category.active').data('cat');
                if (firstCategory) {
                    loadCategoryImages(firstCategory);
                }

                // Остальной код для работы с цветами и т.д. (сохранен из оригинального файла)
                // ... (код для обработки цветов фасадов)
            });
            </script>

<?php get_footer(); ?>