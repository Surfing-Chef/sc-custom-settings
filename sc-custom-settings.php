<?php
/**
 * Plugin Name: SC Custom Settings
 * Plugin URI: https://github.com/Surfing-Chef/sc-custom-settings
 * Description: Adds a place for custom settings within the WordPress Admin Dashboard Settings
 * Version: 1.0
 * Author: Surfing-Chef
 * Author URI: https://github.com/Surfing-Chef
 * License: MIT
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: sc-custom-settings

*/
// add_action( 'admin_init', 'sc_plugin_admin_init' );
// add_action( 'admin_menu', 'sc_create_menu_page' );
//
// /**
//  * Register our stylesheet.
//  */
// function sc_plugin_admin_init() {
//
//   //Register stylesheet
//   wp_register_style( 'scPluginStylesheet', plugins_url( 'styles.css', __FILE__ ) );
// }

/**
 * Register our plugin page and hook stylesheet loading.
 */
// function sc_create_menu_page() {
//     $page = add_submenu_page(
//   		'options-general.php',
//   		'SC Custom Settings',
//   		'SC Custom Settings',
//   		'administrator',
//   		'sc-custom-settings',
//   		'sc_custom_settings_callback'
//   	);
//
//     add_action( "admin_print_styles-{$page}", 'sc_plugin_admin_styles' );
// }

/**
 * Enqueue our stylesheet.
 */
// function sc_plugin_admin_styles() {
//     wp_enqueue_style( 'scPluginStylesheet' );
// }

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */

/**
 * Initializes the theme options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */
add_action('admin_init', 'sc_initialize_custom_settings');
function sc_initialize_custom_settings() {
  // Enqueue our stylesheet
  wp_register_style( 'scPluginStylesheet', plugins_url( 'styles.css', __FILE__ ) );
  wp_enqueue_style( 'scPluginStylesheet' );

    // Register a section. This is necessary since all future options must belong to one.
    add_settings_section(
        'sc_settings_section',         // ID used to identify this section and with which to register options
        'SC Custom Settings',          // Title to be displayed on the administration page
        'sc_custom_settings_callback', // Callback used to render the description of the section
        'general'                      // Page on which to add this section of options
    );

    // Next, we will introduce the fields for toggling the visibility of content elements.
    add_settings_field(
        'sc_google_api',                      // ID used to identify the field throughout the theme
        'Google API',                           // The label to the left of the option interface element
        'sc_toggle_header_callback',   // The name of the function responsible for rendering the option interface
        'general',                          // The page on which this option will be displayed
        'sc_settings_section',         // The name of the section to which this field belongs
        array(                              // The array of arguments to pass to the callback. In this case, just a description.
            'Activate this setting to display the header.'
        )
    );

    // Finally, we register the fields with WordPress
    register_setting(
        'general',
        'sc_google_api'
    );

} // end sc_initialize_custom_settings

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function provides a simple description for the General Options page.
 *
 * It is called from the 'sc_initialize_custom_settings' function by being passed as a parameter
 * in the add_settings_section function.
 */
function sc_custom_settings_callback() {
    echo '<p>Select which areas of content you wish to display.</p>';
} // end sc_custom_settings_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 *
 * It accepts an array of arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function sc_toggle_header_callback($args) {

    // Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
    $html = '<input type="checkbox" id="show_header" name="show_header" value="1" ' . checked(1, get_option('show_header'), false) . '/>';

    // Here, we will take the first argument of the array and add it to a label next to the checkbox
    $html .= '<label for="show_header"> '  . $args[0] . '</label>';

    echo $html;

} // end sc_toggle_header_callback

function sc_create_menu_page() {

    add_menu_page(
        'SC Custom Settings',    // The title to be displayed on the corresponding page for this menu
        'SC Custom Settings',    // The text to be displayed for this actual menu item
        'administrator',         // Which type of users can see this menu
        'sc-custom-settings',    // The unique ID - that is, the slug - for this menu item
        'sc_menu_page_display',  // The name of the function to call when rendering the menu for this page
        ''
    );

    add_submenu_page(
    		'options-general.php',        // Register this submenu with the menu defined above
    		'SC Custom Settings',         // The text to the display in the browser when this menu item is active
    		'SC Custom Settings',         // The text for this menu item
    		'administrator',              // Which type of users can see this menu
    		'sc-custom-settings',         // The unique ID - the slug - for this menu item
    		'sc_custom_settings_display' // The function used to render the menu for this page to the screen
  	);

} // end sc_create_menu_page
add_action('admin_menu', 'sc_create_menu_page');

function sc_menu_page_display() {
  // Create a header in the default WordPress 'wrap' container
  $html = '<div class="wrap">';
      // icon here
      $html .= '<h2>Settings</h2>';
  $html .= '</div>';

  // Send the markup to the browser
  echo $html;

} // end sc_menu_page_display

function sc_custom_settings_display() {
  // Create a header in the default WordPress 'wrap' container
    $html = '<div class="wrap">';
        // icon here
        $html .= '<h2>Sandbox Options</h2>';
    $html .= '</div>';

    // Send the markup to the browser
    echo $html;
} // end sc_custom_settings_display
