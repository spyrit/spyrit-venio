<?php
function venio_related_events_shortcode($atts)
{
    ob_start(); ?>
    <div class="venio-shortcode venio-related-events">
    <div class="shortcode-title">Ces événements pourraient vous intéresser</div>

    <?php
    $args = [
        'post_type' => Synchro::POST_TYPE,
        'posts_per_page' => 3,
        'meta_key' => 'begin_date',
        'orderby' => 'meta_value',
        'post__not_in' => $atts,
        'order' => 'ASC',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key'     => 'begin_date',
                'value'   => date('Y-m-d'),
                'compare' => '>=',
            ],
        ],
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()): ?>
        <div class="events-container">
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <a href="<?php the_permalink()?>" title="Accéder à l'événement <?php the_title()?>" class="single-event">
                    <div class="single-agenda-entry">
                        <img src="<?php echo get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : plugins_url('/img/venio-icon.ico', __FILE__); ?>" />
                        <div class="single-agenda-title"><?php the_title(); ?></div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('venio-related-events', 'venio_related_events_shortcode');
