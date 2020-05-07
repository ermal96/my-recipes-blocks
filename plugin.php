<?php
/**
 * Plugin Name:     My Recipes Blocks
 * Plugin URI:      www.ermal.dev
 * Description:     My Recipes Block WordPress Plugin
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
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/class-my-recipes-blocks-initializer.php';
