<?php
function venio_events_shortcode($atts)
{
    ob_start();
    $name = null;
    $date_start = null;
    $date_end = null;
    $metaQuery_args = null;

    if (get_query_var('paged')) {
        $paged = get_query_var('paged');
    } elseif (get_query_var('page')) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }

    if ($_GET) {
        $name = isset($_GET['nom']) ? ($_GET['nom'] ? $_GET['nom'] : null)  : null;
        $date_start = isset($_GET['date_debut']) ? ($_GET['date_debut'] ? $_GET['date_debut'] : null)  : null;
        $date_end = isset($_GET['date_fin']) ? ($_GET['date_fin'] ? $_GET['date_fin'] : null)  : null;

        if ($date_start) {
            $metaQ = [
                'key'     => 'begin_date',
                'value'   => $date_start,
                'compare' => '>=',
            ];
            $metaQuery_args[] = $metaQ;
        }
        if ($date_end) {
            $metaQ = [
                'key'     => 'end_date',
                'value'   => $date_end,
                'compare' => '<=',
            ];
            $metaQuery_args[] = $metaQ;
        }
    } ?>
    <div class="venio-shortcode venio-events">
        <div class="shortcode-title">Rechercher un événement</div>
        <form class="form-inline" method="GET" action="<?php echo get_the_permalink(); ?>">
            <fieldset id="by-name">
                <legend>Par nom</legend>
                <input type="text" name="nom" placeholder="Nom de l'événement" <?php echo $name ? 'value="' . $name . '"' : ''; ?> />
            </fieldset>
            <fieldset id="by-date">
                <legend>Par date</legend>
                <div class="single-input">
                    <label for="date_debut">À partir du</label>
                    <input type="date" name="date_debut" id="date_debut" <?php echo $date_start ? 'value="' . $date_start . '"' : ''; ?> />
                </div>
                <div class="single-input">
                    <label for="date_fin">Jusqu'au</label>
                    <input type="date" name="date_fin" id="date_fin" />
                </div>
            </fieldset>
            <button type="submit" class="btn btn-default pull-right"><?php _e("Rechercher", 'adv_2015'); ?></button>
        </form>
    <?php
    $args = [
        'post_type' => Synchro::POST_TYPE,
        'posts_per_page' => 10,
        'meta_key' => 'begin_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        's' => $name,
        'meta_query' => [
            'relation' => 'AND',
            $metaQuery_args
        ],
        'paged' => $paged,
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()): ?>
        <div class="events-container">
            <?php while ($query->have_posts()): $query->the_post(); ?>
                <a href="<?php the_permalink()?>" title="Accéder à l'événement <?php the_title()?>" class="single-event">
                    <div class="single-event">
                        <img src="<?php echo get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : plugins_url('/img/venio-icon.ico', __FILE__); ?>" />
                        <div class="single-agenda-content">
                            <h3><?php the_title(); ?></h3>
                            <div class="date"><?php echo venio_date(get_post()); ?></div>
                        </div>

                    </div>
                </a>
            <?php endwhile; ?>
            <div class="sv-pagination">
                <?php
                echo paginate_links(array( // Plus d'info sur les arguments possibles  : https://codex.wordpress.org/Function_Reference/paginate_links
                    'base' => str_replace(99999, '%#%', esc_url(get_pagenum_link(99999))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $query->max_num_pages
                )); ?>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('venio-events', 'venio_events_shortcode');
