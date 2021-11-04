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
        <?php $url = get_site_url() . (!get_option('permalink_structure') ? '?evenement=' : '/evenement/') . $event['subdomain'] ?>
        <a href="<?= $url ?>" title="Accéder à l'événement <?= $event['name'] ?>" class="single-event">
            <?php if ($helper->hasThumbnail($event)): ?>
                <img src="<?= $helper->getEventThumbnail($event); ?>">
            <?php else: ?>
                <span class="no-image"></span>
            <?php endif; ?>
            <span class="date"><?= $helper->getFormattedDate($event); ?></span>
            <span class="title"><?= $event['name'] ?></span>
        </a>
    <?php endforeach;
    return ob_get_clean();
}