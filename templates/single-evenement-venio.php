<?php
get_header();
global $wp_query;
$post = $wp_query->post;
?>
<main id="site-content" role="main">
    <article class="post-<?php echo $post->ID; ?> evenement-venio type-evenement-venio hentry" id="post-<?php echo $post->ID; ?>">
        <header class="entry-header has-text-align-center header-footer-group">
            <div class="entry-header-inner section-inner medium">
                <h1 class="entry-title"><?php echo $post->post_title; ?></h1>
                <?php if ($post->post_excerpt) { ?>
                    <div class="sv-intro">
                        <?php echo $post->post_excerpt; ?>
                    </div>
                <?php } ?>
                <div class="sv-date"><?php echo venio_date($post); ?></div>
            </div>
            <?php
            if (has_post_thumbnail() && ! post_password_required()) {
                $featured_media_inner_classes = '';
                if (! is_singular()) {
                    $featured_media_inner_classes .= ' medium';
                } ?>
                <figure class="featured-media">
                    <div class="featured-media-inner section-inner<?php echo $featured_media_inner_classes; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output?>">
                        <?php
                        the_post_thumbnail();
                $caption = get_the_post_thumbnail_caption();
                if ($caption) {
                    ?>
                            <figcaption class="wp-caption-text"><?php echo esc_html($caption); ?></figcaption>
                            <?php
                } ?>
                    </div><!-- .featured-media-inner -->
                </figure><!-- .featured-media -->
                <?php
            }
            ?>
            <div class="post-inner">
                <div class="entry-content">
                    <p><?php echo $post->post_content; ?></p>
                    <?php echo do_shortcode('[venio-related-events exclude=' . $post->ID . ']') ?>
                </div>
            </div>

        </header>
    </article>
</main>
<?php
get_footer();
