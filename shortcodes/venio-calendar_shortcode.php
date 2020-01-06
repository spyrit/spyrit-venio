<?php
function venio_calendar_shortcode($atts)
{
    ob_start(); ?>
    <div class="venio-shortcode venio-calendar">
    <?php
    $args = [
        'post_type' => Synchro::POST_TYPE,
        'posts_per_page' => -1,
    ];
    $noposts = false;
    $query = new WP_Query($args);

    if ($query->have_posts()): ?>
        <div class="events-container">

            <?php $i=0;
    $eventsArray = [];
    while ($query->have_posts()): $query->the_post();

    $eventsArray[$i] = [
                    'title' => get_the_title(),
                    'url' => get_the_permalink(),
                    'start' => get_post_meta(get_the_ID(), 'begin_date')[0],
                    'end'   => get_post_meta(get_the_ID(), 'end_date')[0],
                    'allDay' => true,
                ];
    $eventsArray[$i] = json_encode($eventsArray[$i]);

    $i++;
    endwhile; ?>
        </div>
    <?php endif; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'dayGrid', 'timeGrid' ],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    <?php
                    foreach ($eventsArray as $singleEvent) {
                        $event = str_replace("&rsquo;", "'", $singleEvent);
                        $event = str_replace("&#8211;", "-", $event);
                        echo $event . ',';
                    } ?>
                ]
            });
            calendar.setOption('locale', 'fr');
            <?php if (!$noposts) {
                        echo "calendar.render();";
                    } ?>
        });
    </script>
    <div id='calendar'></div>
    <?php
    return ob_get_clean();
}
add_shortcode('venio-calendar', 'venio_calendar_shortcode');
