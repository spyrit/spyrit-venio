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
                    <div class="intro-text section-inner max-percentage small">
                        <?php echo $post->post_excerpt; ?>
                    </div>
                <?php } ?>
                <div class="date"><?php echo venio_date($post); ?></div>
                <div class="post-inner">
                    <div class="entry-content">
                        <?php echo $post->post_content; ?>
                    </div>
                </div>
            </div>
        </header>
    </article>
</main>
<?php
get_footer();
