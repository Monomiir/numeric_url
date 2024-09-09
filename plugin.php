<?php
/*
Plugin Name: Numeric URLs
Plugin URI: https://github.com/Monomiir/numeric_url
Description: Generate YOURLS short URLs with numbers only and customizable length.
Version: 1.1
Author: Monomiir
Author URI: https://2900.org
*/

// Hook into the keyword generation process
yourls_add_filter('random_keyword', 'numeric_urls_random_keyword');

// Function to generate numeric-only keyword with customizable length
function numeric_urls_random_keyword($keyword) {
    // Get the custom length option or use default (5) if not set
    $length = yourls_get_option('numeric_urls_length', 5);

    // Generate a random numeric string with the specified length
    $keyword = '';
    for ($i = 0; $i < $length; $i++) {
        $keyword .= mt_rand(0, 9); // Append a random digit (0-9)
    }

    return $keyword;
}

// Create a custom option in the YOURLS settings
yourls_add_action('plugins_loaded', 'numeric_urls_add_option');

function numeric_urls_add_option() {
    yourls_register_plugin_page('numeric_urls_settings', 'Numeric URL Settings', 'numeric_urls_display_page');
}

// Display the settings page
function numeric_urls_display_page() {
    if ( isset($_POST['numeric_urls_length']) ) {
        $length = intval($_POST['numeric_urls_length']);
        if( $length > 0 ) {
            yourls_update_option('numeric_urls_length', $length);
            echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Please enter a valid length.</p></div>';
        }
    }

    $current_length = yourls_get_option('numeric_urls_length', 5);

    echo <<<HTML
    <h2>Numeric URL Settings</h2>
    <form method="post">
        <p>
            <label for="numeric_urls_length">Numeric URL Length:</label>
            <input type="number" id="numeric_urls_length" name="numeric_urls_length" value="$current_length" min="1">
        </p>
        <p><input type="submit" value="Save Settings"></p>
    </form>
HTML;
}
