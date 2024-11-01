<?php

/**
 * Shortcode [aio_ip] to display the user's IP address.
 *
 * @return string The user's IP address.
 */
function aiosc_shortcode_user_ip() {
    // Get the user's IP address from the $_SERVER variable.
    // Note: This method may not always provide the actual user's IP due to proxies or network configurations.
    return $_SERVER['REMOTE_ADDR'];
}

// Add the shortcode for IP address.
add_shortcode('aio_ip', 'aiosc_shortcode_user_ip');

