<?php
function venio_events_list($events)
{
    $helper = new \VENIO\Helper();
    ob_start();
    for($i = 1; $i <= 4; $i++): ?>
        <div class="placeholder-item" id="eventsPlaceholder">
            <div class="placeholder-image"></div>
            <div class="placeholder-text"></div>
            <div class="placeholder-text"></div>
        </div>
    <?php endfor;
    foreach ($events as $event): ?>
        <a href="<?php echo esc_url($helper->getEventUrl($event)) ?>" title="<?php _e( "Access the event page", 'venio' ); ?> <?php echo esc_html($event['name']) ?>" class="single-event">
            <?php if ($helper->hasThumbnail($event)): ?>
                <img src="<?php echo esc_html($helper->getEventThumbnail($event)) ?>">
            <?php else: ?>
                <span class="no-image"></span>
            <?php endif; ?>
            <span class="date"><?php echo esc_html($helper->getFormattedDate($event)) ?></span>
            <span class="title"><?php echo esc_html($event['name']) ?></span>
        </a>
    <?php endforeach;
    return ob_get_clean();
}