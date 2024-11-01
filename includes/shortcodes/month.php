<?php

/**
 * Shortcode: [aio_month]
 * Description: Renders the current month.
 * Example usage: [aio_month] (Output: May)
 *                [aio_month go="-2"] (Output: May) // Default: Current month
 *                [aio_month go="5"]  (Output: December) // 5 months after the current month
 * Attribute 'format': 'mm' for 01, 02, 25, 29, 31
 *                     'mmm' for Jan, Feb, Oct, Dec
 *                     'mmmm' for January, February full names (Default)
 */
function aiosc_combined_shortcode_month($atts)
{
    $atts = shortcode_atts(array(
        'go' => 0,
        'format' => 'mmmm', // Default format is 'mmmm'
    ), $atts);

    $offset = intval($atts['go']); // go the integer value of the months offset
    $format = strtolower($atts['format']); // Convert the format attribute to lowercase

    // Validate the 'format' attribute and set the date format accordingly
    if ($format === 'mm') {
        $date_format = 'm';
    } elseif ($format === 'mmm') {
        $date_format = 'M';
    } elseif ($format === 'mmmm') {
        $date_format = 'F';
    } else {
        $date_format = 'F'; // Default to 'mmmm' (full month name) if an invalid format is provided
    }

    // If 'go' attribute is specified, calculate the month based on the offset
    if ($offset !== 0) {
        return date($date_format, strtotime("{$offset} months"));
    }

    // If 'go' attribute is not specified, return the current month in the desired format
    return date($date_format);
}

add_shortcode("aio_month", "aiosc_combined_shortcode_month"); // Change the shortcode name here
