<?php

/**
 * Shortcode: [aio_year]
 * Description: Renders the current year or the year from a specific number of years before or after the current year.
 * Example usage: [aio_year] (Output: 2023) // Default: Current year
 *                [aio_year go="-2"] (Output: 2021) // 2 years before the current year
 *                [aio_year go="5"]  (Output: 2028) // 5 years after the current year
 * Attribute 'format': 'yy' for 23, 25, 01 (year numbers)
 *                     'yyyy' for full year like 2023, 2025, 2045 (Default)
 */
function aiosc_combined_shortcode_year($atts)
{
    $atts = shortcode_atts(array(
        'go' => 0,
        'format' => 'yyyy', // Default format is 'yyyy'
    ), $atts);

    $offset = intval($atts['go']); // go the integer value of the years offset
    $format = strtolower($atts['format']); // Convert the format attribute to lowercase

    // Validate the 'format' attribute and set the date format accordingly
    if ($format === 'yy') {
        $date_format = 'y';
    } elseif ($format === 'yyyy') {
        $date_format = 'Y';
    } else {
        $date_format = 'Y'; // Default to 'yyyy' if an invalid format is provided
    }

    // If 'go' attribute is specified, calculate the year based on the offset
    if ($offset !== 0) {
        return date($date_format, strtotime("{$offset} years"));
    }

    // If 'go' attribute is not specified, return the current year in the desired format
    return date($date_format);
}

add_shortcode('aio_year', 'aiosc_combined_shortcode_year');


// Shortcode to display the current day of the year
function aiosc_shortcode_day_year()
{
    $current_day = date('z') + 1; // z returns the day of the year (0 to 365), so adding 1 to start from 1
    return $current_day;
}
add_shortcode('aio_day_year', 'aiosc_shortcode_day_year');

// Shortcode to display the current week number of the year
function aiosc_shortcode_week_year()
{
    $current_week = date('W'); // W returns the week number of the year (1 to 52/53)
    return $current_week;
}
add_shortcode('aio_week_year', 'aiosc_shortcode_week_year');


// Shortcode to display the current quarter of the year
function aiosc_shortcode_quarter_year()
{
    $current_month = date('n'); // n returns the numeric representation of the current month (1 to 12)
    $current_quarter = ceil($current_month / 3); // Calculate the quarter from the month
    return $current_quarter;
}
add_shortcode('aio_quarter_year', 'aiosc_shortcode_quarter_year');


// Shortcode to display the total number of days in the year with dynamic year offset
function aiosc_shortcode_days_year($atts)
{
    $atts = shortcode_atts(array(
        'go' => 0,
    ), $atts);

    // If 'go' attribute is specified, calculate the total days based on the offset year
    if (isset($atts['go'])) {
        $year_offset = intval($atts['go']);
        $current_year = date('Y') + $year_offset;
    } else {
        // If 'go' attribute is not specified, use the current year
        $current_year = date('Y');
    }

    // Calculate the total days in the year
    $start_date = strtotime($current_year . '-01-01');
    $end_date = strtotime($current_year . '-12-31');
    $total_days = ($end_date - $start_date) / (60 * 60 * 24) + 1;

    return $total_days;
}
add_shortcode('aio_days_year', 'aiosc_shortcode_days_year');


/**
 * Shortcode: [[aio_first_day_year]]
 * Description: Renders the day name of the first day of the current year or a dynamic year offset.
 * Example usage: [[aio_first_day_year]] (Output: Friday) // The day name of the first day of the current year
 *                [[aio_first_day_year go="-2"]] (Output: Wednesday) // The day name of the first day of the year 2 years before the current year
 *                [[aio_first_day_year go="5"]] (Output: Thursday) // The day name of the first day of the year 5 years after the current year
 *                [[aio_first_day_year format="short"]] (Output: Fri) // The short day name of the first day of the current year
 *                [[aio_first_day_year format="short" go="5"]] (Output: Thu) // The short day name of the first day of the year 5 years after the current year
 */
function aio_shortcode_first_day_of_year($atts)
{
    $atts = shortcode_atts(array(
        'go' => 0,
        'format' => 'default',
    ), $atts);

    $year_offset = intval($atts['go']);
    $current_year = date('Y') + $year_offset;

    $format = strtolower($atts['format']);
    if ($format === 'short') {
        $first_day_name = date('D', mktime(0, 0, 0, 1, 1, $current_year));
    } else {
        $first_day_name = date('l', mktime(0, 0, 0, 1, 1, $current_year));
    }

    return $first_day_name;
}

add_shortcode('aio_first_day_year', 'aio_shortcode_first_day_of_year');


/**
 * Shortcode: [aio_last_day_year]
 * Description: Renders the day name of the last day of the current year or a dynamic year offset.
 * Example usage: [[aio_last_day_year]] (Output: Friday) // The day name of the last day of the current year
 *                [[aio_last_day_year go="-2"]] (Output: Monday) // The day name of the last day of the year 2 years before the current year
 *                [[aio_last_day_year go="5"]] (Output: Wednesday) // The day name of the last day of the year 5 years after the current year
 *                [[aio_last_day_year format="short"]] (Output: Fri) // The short day name of the last day of the current year
 *                [[aio_last_day_year format="short" go="5"]] (Output: Thu) // The short day name of the last day of the year 5 years after the current year
 */
function aio_shortcode_last_day_of_year($atts)
{
    $atts = shortcode_atts(array(
        'go' => 0,
        'format' => 'default',
    ), $atts);

    $year_offset = intval($atts['go']);
    $current_year = date('Y') + $year_offset;

    $format = strtolower($atts['format']);
    if ($format === 'short') {
        $last_day_name = date('D', mktime(0, 0, 0, 12, 31, $current_year));
    } else {
        $last_day_name = date('l', mktime(0, 0, 0, 12, 31, $current_year));
    }

    return $last_day_name;
}

add_shortcode('aio_last_day_year', 'aio_shortcode_last_day_of_year');
