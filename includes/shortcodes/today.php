<?php

/**
 * Shortcode: [aio_today]
 * Description: Renders today's day of the week in the full name form (e.g., Friday, Sunday).
 * Example usage: [aio_today]
 */
function aiosc_shortcode_today($atts)
{
    // Set default attributes
    $atts = shortcode_atts(array(
        'go' => '0',
        'format' => 'full', // Default to full day name
    ), $atts);

    $offset = intval($atts['go']); // go the integer value of the days offset

    // If 'go' attribute is specified, calculate the date based on the offset
    if ($offset !== 0) {
        $newDate = date("Y-m-d", strtotime("{$offset} days"));
        return aiosc_format_day_of_week($atts['format'], strtotime($newDate));
    }

    // If 'go' attribute is not specified, return today's day of the week
    return aiosc_format_day_of_week($atts['format'], time());
}

// Function to format the day of the week based on the format attribute
function aiosc_format_day_of_week($format, $timestamp)
{
    if ($format === 'short') {
        return date("D", $timestamp);
    } else {
        return date("l", $timestamp);
    }
}

add_shortcode("aio_today", "aiosc_shortcode_today");
