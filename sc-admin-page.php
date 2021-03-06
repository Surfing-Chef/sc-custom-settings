<?php
/**
 * Plugin Name: SC Custom Settings
 * Plugin URI: https://github.com/Surfing-Chef/sc-custom-settings
 * Description: Creates a custom options page on the WordPress dashboard menu
 * Version: 1.1
 * Author: Surfing-Chef
 * Author URI: https://github.com/Surfing-Chef
 * License: MIT
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: sc
 *
*/

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

// SETTINGS AND FIELDS
 function sc_settings_init() {
  // register a new setting for "sc-settings" page
  register_setting(
    'sc_settings',                         // $option_group
    'sc_settings_options'                  // $option_name
  );

  // register a new setting for "sc-settings" page
  add_settings_section(
    'sc_settings_section_id',              // $id
    __('SC Section Title', 'sc'),          // $title
    'sc_settings_section_cb',              // $callback
    'sc_settings_page'                     // $page
  );

  // register a new field in the "sc_settings_section_id" section, inside the "sc-settings" page
  add_settings_field(
    // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback

    'sc_settings_field_id',                // $id (used only internally as of WP 4.6, use $args' label_for to populate the id inside the callback)
    __('Google Maps API', 'sc'),           // $title
    'sc_settings_field_cb',                // $callback
    'sc_settings_page',                    // $page
    'sc_settings_section_id',              // $section
    [                                      // $args
      'label_for' => 'sc_settings_field_id',   // setting title will be wrapped in a <label> element, its for attribute populated with this value
      'class' => 'sc_settings_row',            // CSS Class to be added to the <tr> element when the field is output
      'sc_settings_custom_data' => 'custom',   // custom data
    ]
  );
 }
 // END function sc_settings_init()

  // register sc_settings_init to the admin_init action hook
  add_action( 'admin_init', 'sc_settings_init' );

// SETTINGS AND FIELD CALLBACKS
//
// Section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// The values are auto-defined at the add_settings_section() function.
//
// Field callbacks can accept an $args parameter, which is an array.
// $args is auto-defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.

// Define section components
// Called by add_settings_section()
function sc_settings_section_cb( $args ){
  ?>
   <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'A valid Google Maps API goes here.', 'sc' ); ?></p>
 <?php
}

// Define field components
// Called by add_settings_field()
function sc_settings_field_cb( $args ){

  // Get the value of the registered setting
  // $option_name from register_setting()
  $options = get_option( 'sc_settings_options' );
  // output the field
  ?>
  <input id="<?php echo esc_attr( $args['label_for'] ); ?>"
         type="text"
         data-custom="<?php echo esc_attr( $args['sc_settings_custom_data'] ); ?>"
         name="sc_settings_options[<?php echo esc_attr( $args['label_for'] ); ?>]">

  <p class="description">
  <?php esc_html_e( 'Enter a valid Googgle Map AP.', 'sc' ); ?>
  </p>
  <?php
}

// TOP LEVEL MENU

// Create the options page that displays the sections and fields
function sc_options_page(){
  add_menu_page(
    __('SC Options', 'sc'),           // $page_title
    __('SC Options', 'sc'),           // $menu_title
    'manage_options',                 // $capability
    'sc_custom_options',                  // $menu_slug
    'sc_options_page_html'            // $function
  );
}

// Register sc_options_page to the admin_menu action hook
add_action('admin_menu', 'sc_options_page');

// TOP LEVEL MENU CALLBACKS

// Populate the options page with sections and fields
function sc_options_page_html(){
  // check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  // Add Error/Update Messages

  // Check if the user have submitted the settings
  // WordPress will add the "settings-updated" $_GET parameter to the url
  if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'sc_messages', 'sc_message', __( 'Settings Saved', 'sc' ), 'updated' );
  }

  // show error/update messages
  settings_errors( 'wporg_messages' );
  ?>

  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
      <?php
      // $option_group from register_setting()
      settings_fields( 'sc_settings' );

      // Output setting sections and their fields
      // Sections are registered for "sc_settings", each field is registered to a specific section
      // $page from add_settings_section() and add_settings_field()
      do_settings_sections( 'sc_settings_page' );

      // output save settings button
      submit_button( 'Save Settings' );
      ?>
    </form>
  </div>
  <?php
}
