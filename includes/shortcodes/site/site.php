<?php

/**
* Shortcode: [aio_site_title]
* Description: Displays the site title from WordPress settings.
* Example usage: [aio_site_title]
*/
function aiosc_site_title_shortcode() {
// Get the site title from WordPress settings
$site_title = get_bloginfo('name');

// Output the site title
return $site_title;
}

// Register the shortcode
add_shortcode('aio_site_title', 'aiosc_site_title_shortcode');


/**
* Shortcode: [aio_site_tagline]
* Description: Displays the tagline from WordPress settings.
* Example usage: [aio_site_tagline]
*/
function aiosc_site_tagline_shortcode() {
// Get the site tagline from WordPress settings
$site_tagline = get_bloginfo('description');

// Output the site tagline
return $site_tagline;
}

// Register the shortcode
add_shortcode('aio_site_tagline', 'aiosc_site_tagline_shortcode');


/**
* Shortcode: [aio_site_url]
* Description: Displays the Site URL of the site as a clickable link.
* Example usage: [aio_site_url link="yes" new_window="yes"]
*/
function aiosc_site_url_shortcode($atts) {
// Set default attribute values
$atts = shortcode_atts(array(
'link' => 'yes', // Default value is 'yes'
'new_window' => 'no', // Default value is 'no'
), $atts);

// Get the homepage URL of the site
$home_url = home_url('/');

// Check if 'link' attribute is set to 'yes' and construct the link accordingly
if ($atts['link'] === 'yes') {
$link = '<a href="' . esc_url($home_url) . '"';

// Check if 'new_window' attribute is set to 'yes' and add the target="_blank" attribute
if ($atts['new_window'] === 'yes') {
$link .= ' target="_blank"';
}

$link .= '>' . esc_url($home_url) . '</a>';

return $link;
}

// If 'link' attribute is not set to 'yes', just return the URL
return esc_url($home_url);
}

// Register the shortcode
add_shortcode('aio_site_url', 'aiosc_site_url_shortcode');


/**
* Shortcode: [aio_home_url]
* Description: Displays the homepage URL of the site as a clickable link.
* Example usage: [aio_home_url link="yes" new_window="yes"]
*/
function aiosc_home_url_shortcode($atts) {
// Set default attribute values
$atts = shortcode_atts(array(
'link' => 'yes', // Default value is 'yes'
'new_window' => 'no', // Default value is 'no'
), $atts);

// Get the homepage URL of the site
$home_url = home_url('/');

// Check if 'link' attribute is set to 'yes' and construct the link accordingly
if ($atts['link'] === 'yes') {
$link = '<a href="' . esc_url($home_url) . '"';

// Check if 'new_window' attribute is set to 'yes' and add the target="_blank" attribute
if ($atts['new_window'] === 'yes') {
$link .= ' target="_blank"';
}

$link .= '>' . esc_url($home_url) . '</a>';

return $link;
}

// If 'link' attribute is not set to 'yes', just return the URL
return esc_url($home_url);
}

// Register the shortcode
add_shortcode('aio_home_url', 'aiosc_home_url_shortcode');