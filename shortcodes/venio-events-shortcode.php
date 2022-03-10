<?php
function venio_events_shortcode($atts)
{
    $institution = isset($atts['institution']) && $atts['institution'] ? esc_html($atts['institution']) : null;
    $api = new \VENIO\Api();
    $events = $api->getEvents($institution);
    ob_start();
    ?>
    <div class="venio-shortcode venio-events">
    <?php if ($events): ?>
        <form action="#" id="eventsForm" onsubmit="return eventsFormSubmit()">
            <label for="name"><?php _e( 'Search by name', 'venio'); ?></label>
            <div class="form-wrapper">
                <input type="text" name="name" id="name" placeholder="<?php _e( 'Search by name', 'venio'); ?>" />
                <button type="submit"><?php _e('Search', 'venio'); ?></button>
            </div>
        </form>
        <div class="events-container" id="eventsContainer">
            <?php echo wp_kses(venio_events_list($events), ['a' => ['href' => [], 'title' => [], 'class' => []], 'img' => ['src' => []], 'span' => ['class' => []], 'div' => ['class' => []]]); ?>
        </div>
        <script>
            let eventsContainer = document.getElementById("eventsContainer");
            if (eventsContainer.offsetWidth >= 800){eventsContainer.classList.add("fourC");} else if (eventsContainer.offsetWidth >= 600){eventsContainer.classList.add("threeC");}
            let eventsPlaceholder = document.getElementById("eventsPlaceholder");
            function eventsFormSubmit()
            {
                eventsContainer.classList.add("loading");
                let searchVal = document.getElementById('name').value;
                let request = new XMLHttpRequest();
                request.open('POST', "/wp-admin/admin-ajax.php", true);
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
                request.onload = function () {
                    if (this.status >= 200 && this.status < 400) {
                        var response = JSON.parse(this.response);
                        if (response.data) {
                            setTimeout(function () {
                                eventsContainer.classList.remove("loading");
                                eventsContainer.innerHTML = JSON.parse(response.data);
                            }, 300);
                        }
                    }
                };
                let institution = '<?php echo esc_html($institution) ?>';
                request.send('action=get_events_by_string&search='+searchVal+'&institution='+institution);
                return false;
            }
        </script>
    <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('venio-events', 'venio_events_shortcode');
