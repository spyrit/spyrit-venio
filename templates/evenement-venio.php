<?php
global $wp_query;

use VENIO\Api;
use VENIO\Helper;

$event = null;
$api = new Api();
$helper = new Helper();
if (isset($wp_query->query['evenement']) && $wp_query->query['evenement']) {
    $event = $api->getEvents(null, $wp_query->query['evenement']);
}
if (!$event || !isset($wp_query->query['evenement'])) {
    $wp_query->set_404();
    add_action( 'wp_title', function () {
        return '404: Not Found';
    }, 9999);
    status_header(404);
    nocache_headers();
    require get_404_template();
    exit;
}

?>

<?php get_header(); ?>

<main id="site-content" class="venio-single" role="main">
    <h1><?= $event['name'] ?></h1>
    <div class="date"><?= $helper->getFormattedDate($event); ?></div>
    <?php if($helper->hasThumbnail($event)): ?>
        <div class="venio-slider-container">
            <div class="venio-slider-controls">
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <?php foreach ($helper->getEventMedias($event) as $imageUrl): ?>
                <div class="venio-slide" style="background-image:url('<?= $imageUrl ?>');"></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($event['long_description']): ?>
        <div class="info-block">
            <span class="block-title">Description</span>
            <div class="block-content"><?= $event['long_description']; ?></div>
        </div>
    <?php endif; ?>
    <?php if ($event['practical_informations']): ?>
        <div class="info-block">
            <span class="block-title"><?php _e( 'Practical informations', 'venio' ); ?></span>
            <div class="block-content"><?= $event['practical_informations']; ?></div>
        </div>
    <?php endif; ?>
    <?php if ($event['program']): ?>
        <div class="info-block">
            <span class="block-title"><?php _e( 'Program', 'venio' ); ?></span>
            <div class="block-content"><?= $event['program']; ?></div>
        </div>
    <?php endif; ?>
    <?php if(isset($event['packages']) && count($event['packages']) > 0): ?>
        <div class="info-block">
            <span class="block-title"><?php _e( 'Packages list', 'venio' ); ?></span>
        </div>
        <?php foreach ($event['packages'] as $package): ?>
            <div class="info-block package">
                <span class="block-title"><?php _e( 'Package', 'venio' ); ?> <?= $package['name'] ?></span>
                <div class="block-content">
                    <?= $package['short_description']; ?>
                    <a href="<?= 'https://'.$event['subdomain'].'.venio.fr/fr/package/'.$package['id'].'/registration/create'?>" title="<?php _e( 'Register with the package', 'venio' ); ?> <?= $package['name']?>" class="outer-link" onclick="return confirm('<?php _e( 'You will leave the site to register on', 'venio' ); ?> venio.fr');" target="_blank">
                        <?php _e( 'Choose this package', 'venio' ); ?>
                    </a>
                    <div class="price"><?= $package['minimum_price'] ? __( 'Starting from', 'venio' ) . ' ' . $package['minimum_price'] . "&nbsp;€": '' ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
        var slideIndex = 1;
        showSlides(slideIndex);
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("venio-slide");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex-1].style.display = "block";
        }
    </script>
</main>

<?php get_footer(); ?>
