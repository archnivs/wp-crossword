<?php

/**
 *
 * @link              #
 * @since             1.0.0
 * @package           Wp_Crossword
 *
 * @wordpress-plugin
 * Plugin Name:       WP Crossword
 * Plugin URI:        #
 * Description:       A simple crossword puzzle.
 * Version:           1.0.0
 * Author:            Nivs
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-crossword
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('WP_CROSSWORD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('WP_CROSSWORD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-crossword-activator.php
 */
function activate_wp_crossword() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-crossword-activator.php';
	Wp_Crossword_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-crossword-deactivator.php
 */
function deactivate_wp_crossword() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-crossword-deactivator.php';
	Wp_Crossword_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_crossword' );
register_deactivation_hook( __FILE__, 'deactivate_wp_crossword' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-crossword.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_crossword() {

	$plugin = new Wp_Crossword();
	$plugin->run();

}
run_wp_crossword();
