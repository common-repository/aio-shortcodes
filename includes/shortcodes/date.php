<?php

/**
 * Shortcode: [aio_date]
 * Description: Renders today's date.
 * Example usage: [aio_date] (Output: August 10, 2023)
 */

 function aiosc_shortcode_relative_date($atts)
 {
     // Extract shortcode attributes
     $atts = shortcode_atts(
         array(
             'go' => 'next',
             'format' => '' // Default format is an empty string
         ),
         $atts,
         'aio_date' // Change the shortcode name here
     );
 
     // go the number from the 'go' attribute
     $number = intval($atts['go']);
 
     // go the current timestamp
     $current_timestamp = time();
 
     // Calculate the new timestamp based on the 'go' attribute
     if ($number >= 0) {
         $new_timestamp = strtotime("+$number days", $current_timestamp);
     } else {
         $new_timestamp = strtotime("$number days", $current_timestamp);
     }
 
     // If the format is not provided, use the default format
     if (empty($atts['format'])) {
         return date('F j, Y', $new_timestamp); // Change the date format here
     }
 
     // Custom placeholders for day, month, and year formats
     $placeholders = array(
         'dd' => date('d', $new_timestamp),
         'd' => date('j', $new_timestamp),
         'mm' => date('m', $new_timestamp),
         'mmm' => date('M', $new_timestamp),
         'mmmm' => date('F', $new_timestamp),
         'yyyy' => date('Y', $new_timestamp),
         'yy' => date('y', $new_timestamp),
         'dS' => date('jS', $new_timestamp),
         'ddS' => date('jS', $new_timestamp),
     );
 
     // Replace the custom placeholders in the format string
     $formatted_date = strtr($atts['format'], $placeholders);
 
     return $formatted_date;
 }
 
 add_shortcode('aio_date', 'aiosc_shortcode_relative_date'); // Change the shortcode name here
 