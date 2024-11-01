<?php

/**
 * Shortcode: [aio_email]
 * Description: Renders the specified email address.
 * Example usage: [aio_email send="harpreet@wpblogging101.com" output="emailaddress"]
 */
function aiosc_shortcode_email($atts)
{
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'send' => '',
        'output' => 'emailaddress',
    ), $atts);

    // Sanitize the email address to prevent spam
    $email = sanitize_email($atts['send']);

    // Output the email address with the specified text
    return '<a href="mailto:' . $email . '">' . esc_html($atts['output']) . '</a>';
}
add_shortcode('aio_email', 'aiosc_shortcode_email');

/**
 * Shortcode: [aio_whatsapp]
 * Description: Renders a WhatsApp link with the specified phone number and message.
 * Example usage: [aio_whatsapp send="+919876543210" text="this is my WhatsApp message!"]
 */
function aiosc_shortcode_whatsapp($atts)
{
    // Extract shortcode attributes
    $atts = shortcode_atts(array(
        'send' => '',
        'text' => 'Send message',
    ), $atts);

    // URL encode the phone number and message text
    $phone_number = rawurlencode($atts['send']);
    $message = rawurlencode($atts['text']);

    // Generate the WhatsApp link
    $whatsapp_link = 'https://wa.me/' . $phone_number . '?text=' . $message;

    // Output the WhatsApp link with the specified text
    return '<a href="' . esc_url($whatsapp_link) . '">' . esc_html($atts['text']) . '</a>';
}
add_shortcode('aio_whatsapp', 'aiosc_shortcode_whatsapp');

/**
 * Shortcode: [aio_telephone]
 * Description: Renders a telephone link with the specified phone number and text.
 * Example usage: [aio_telephone number="+1234567890" text="Call me"]
 */
function aiosc_shortcode_telephone($atts)
{
    // Set default attributes
    $atts = shortcode_atts(array(
        'number' => '',
        'text' => '',
    ), $atts);

    // URL encode the phone number
    $phone_number = rawurlencode($atts['number']);

    // Generate the telephone link
    $telephone_link = 'tel:' . $phone_number;

    // Output the telephone link with the specified text or the contact number if text is empty
    $link_text = !empty($atts['text']) ? esc_html($atts['text']) : $atts['number'];

    // Return the link with the specified text or contact number
    return '<a href="' . esc_url($telephone_link) . '">' . esc_html($link_text) . '</a>';
}
add_shortcode('aio_telephone', 'aiosc_shortcode_telephone');

/**
 * Shortcode: [aio_mobile]
 * Description: Renders a mobile link with the specified phone number and text.
 * Example usage: [aio_mobile number="+1234567890" text="Call me"]
 */
function aiosc_shortcode_mobile($atts)
{
    // Set default attributes
    $atts = shortcode_atts(array(
        'number' => '',
        'text' => '',
    ), $atts);

    // URL encode the phone number
    $phone_number = rawurlencode($atts['number']);

    // Generate the mobile link
    $mobile_link = 'tel:' . $phone_number;

    // Output the mobile link with the specified text or the contact number if text is empty
    $link_text = !empty($atts['text']) ? esc_html($atts['text']) : $atts['number'];

    // Return the link with the specified text or contact number
    return '<a href="' . esc_url($mobile_link) . '">' . esc_html($link_text) . '</a>';
}
add_shortcode('aio_mobile', 'aiosc_shortcode_mobile');
