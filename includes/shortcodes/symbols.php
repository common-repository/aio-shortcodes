<?php

/**
 * Shortcode: [aio_c]
 * Description: Renders the copyright symbol (©).
 * Example usage: [aio_c]
 */
function aiosc_shortcode_copyright()
{
    return '©';
}
add_shortcode('aio_c', 'aiosc_shortcode_copyright');


/**
 * Shortcode: [aio_r]
 * Description: Renders the registered trademark symbol (®).
 * Example usage: [aio_r]
 */
function aiosc_shortcode_registered_trademark_short()
{
    return '®';
}
add_shortcode('aio_r', 'aiosc_shortcode_registered_trademark_short');

/**
 * Shortcode: [aio_rt]
 * Description: Renders the registered trademark symbol (®).
 * Example usage: [aio_rt]
 */
function aiosc_shortcode_registered_trademark()
{
    return '®';
}
add_shortcode('aio_rt', 'aiosc_shortcode_registered_trademark');


/**
 * Shortcode: [aio_tm]
 * Description: Renders the unregistered trademark symbol (™).
 * Example usage: [aio_tm]
 */
function aiosc_shortcode_unregistered_trademark()
{
    return '™';
}
add_shortcode('aio_tm', 'aiosc_shortcode_unregistered_trademark');

/**
 * Shortcode: [aio_sm]
 * Description: Renders the service mark symbol (℠).
 * Example usage: [aio_sm]
 */
function aiosc_shortcode_service_mark()
{
    return '℠';
}
add_shortcode('aio_sm', 'aiosc_shortcode_service_mark');
