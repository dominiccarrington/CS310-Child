<?php
/**
 * Plugin Name:         Child
 * Version:             1.0.0
 * Plugin URI:          http://dominiccarrington.github.io/university/project
 * Description:         The child plugin for my WordPress management solution
 * Author:              Dominic Carrington
 * Author URI:          http://www.hughlashbrooke.com/
 * Requires at least:   5.5
 * Tested up to:        5.5
 * Requires PHP:        7.2
 *
 * Text Domain:         child
 * Domain Path:         /lang/
 *
 * @package WordPress
 * @author Dominic Carrington
 * @copyright 2020 Dominic Carrington
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once 'includes/class-child.php';
require_once 'includes/class-child-settings.php';

// Load plugin libraries.
require_once 'includes/lib/class-child-admin-api.php';
require_once 'includes/lib/class-child-post-type.php';
require_once 'includes/lib/class-child-taxonomy.php';

/**
 * Returns the main instance of Child to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Child
 */
function child() {
	$instance = Child::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Child_Settings::instance( $instance );
	}

	return $instance;
}

child();
