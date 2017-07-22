<?php
/**
 * Plugin Name: SC Custom Settings
 * Plugin URI: https://github.com/Surfing-Chef/sc-custom-settings
 * Description: Creates an custom options page on the WordPress dashboard menu
 * Version: 1.0
 * Author: Surfing-Chef
 * Author URI: https://github.com/Surfing-Chef
 * License: MIT
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: sc-custom-settings
 *
*/

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function sc_settings_init() {
 // register a new setting for "sc" page
 register_setting( 'sc', 'sc_options' );

 // register a new section in the "sc" page
 add_settings_section(
 'sc_section_developers',
 __( 'APIs', 'sc' ),
 'sc_section_developers_cb',
 'sc'
 );

 // register a new field in the "sc_section_developers" section, inside the "sc" page
 add_settings_field(
 'sc_field_googleMaps', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 __( 'Google Maps API', 'sc' ),
 'sc_field_googleMaps_cb',
 'sc',
 'sc_section_developers',
 [
 'label_for' => 'sc_field_googleMaps',
 'class' => 'sc_row',
 'sc_custom_data' => 'custom',
 ]
 );
}

/**
 * register our sc_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'sc_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function sc_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Developer APIs used.', 'sc' ); ?></p>
 <?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function sc_field_googleMaps_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sc_options' );

 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 data-custom="<?php echo esc_attr( $args['sc_custom_data'] ); ?>"
 name="sc_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >
 <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'red pill', 'sc' ); ?>
 </option>
 <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'blue pill', 'sc' ); ?>
 </option>
 </select>
 <p class="description">
 <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'sc' ); ?>
 </p>
 <?php
}

/**
 * top level menu
 */
function sc_options_page() {
 // add top level menu page
 add_menu_page(
 'SC Theme Settings',
 'Theme Settings',
 'manage_options',
 'sc',
 'sc_options_page_html'
 );
}

/**
 * register our sc_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'sc_options_page' );

/**
 * top level menu:
 * callback functions
 */
function sc_options_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }

 // add error/update messages

 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'sc_messages', 'sc_message', __( 'Settings Saved', 'sc' ), 'updated' );
 }

 // show error/update messages
 settings_errors( 'sc_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "sc"
 settings_fields( 'sc' );
 // output setting sections and their fields
 // (sections are registered for "sc", each field is registered to a specific section)
 do_settings_sections( 'sc' );
 // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>
 <?php
}
