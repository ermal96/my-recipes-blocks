<?php
/**
 * Plugin Name:     My Recipes Blocks
 * Plugin URI:      www.ermal.dev
 * Description:     
 * Author:          Ermal Vrapi
 * Author URI:      www.ermal.dev
 * Text Domain:     my-recipes-blocks
 * Domain Path:     /src/languages
 * Version:         1.0.0
 *
 * @package         My_Recipes_Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Libraries
 */	
require_once plugin_dir_path( __FILE__ ) . 'library/class-core-blocks.php';

/**
 * Plugin manager
 */
require_once plugin_dir_path( __FILE__ ) . 'class-my-recipes-blocks-admin-page.php';

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/class-my-recipes-blocks-initializer.php';
