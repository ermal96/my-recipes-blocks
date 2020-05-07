<?php
/**
 * Blocks initializer
 *
 * @link       www.ermal.dev
 * @since      1.0.0
 *
 * @package    My_Recipes_Blocks
 * @subpackage My_Recipes_Blocks/src
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class My_Recipes_Blocks_Initializer
 */
class My_Recipes_Blocks_Initializer {

	const NOPRIV_NONCE_ACTION = 'mr-blocks-special-string-for$nopriv%ajax';

	const NONCE_ACTION = 'mr-blocks-special-string-for%ajax';

	/**
	 * My_Recipes_Blocks_Initializer constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_script' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
		//add_filter( 'allowed_block_types', array( $this, 'allowed_block_types' ), 11, 2 );
		add_filter( 'block_categories', array( $this, 'add_category' ), 11, 2 );

		//add_action( 'init', array( $this, 'template_to_posts' ) );
		add_action( 'init', array( $this, 'load_translations' ) );
		//add_action( 'init', array( $this, 'register_callbacks' ) );
		//add_action( 'init', array( $this, 'register_meta' ) );
	}

	/**
	 * Load the plugin domain translations
	 */
	public function load_translations() {
		load_plugin_textdomain( 'my-recipes-blocks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}


	/**
	 * Register public script
	 */
	public function register_public_script() {
    	if ( ! file_exists(plugin_dir_path( __DIR__ ) . 'dist/public-scripts.min.js' ) ) {
    	    return;
    	}
		wp_enqueue_script(
			'my-recipes-blocks-public-scripts',
			plugin_dir_url( __DIR__ ) . 'dist/public-scripts.min.js',
			array(
				'jquery',
			),
			filemtime( plugin_dir_path( __DIR__ ) . 'dist/public-scripts.min.js' ),
			true
		);
		wp_localize_script(
			'my-recipes-blocks-public-scripts',
			'MyRecipesBlocksBlocksAjax',
			array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( self::NOPRIV_NONCE_ACTION ),
			)
		);
	}

	/**
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 *
	 * @since 1.0.0
	 */
	public function block_assets() {
		// Styles.
		$relative_path = 'dist/blocks.style.build.css';
		if ( file_exists( plugin_dir_path( __DIR__ ) . $relative_path ) && filesize( plugin_dir_path( __DIR__ ) . $relative_path ) > 0 ) {
			wp_enqueue_style(
				'crispybacon-blocks-style',
				plugins_url( $relative_path, dirname( __FILE__ ) ),
				array( 'wp-blocks' ),
				filemtime( plugin_dir_path( __DIR__ ) . $relative_path )
			);
		}

	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
	 * `wp-i18n`: To internationalize the block's text.
	 *
	 * @since 1.0.0
	 */
	public function editor_assets() {
		global $current_screen;
		wp_enqueue_script(
			'my-recipes-blocks-build',
			plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ),
			true
		);
		wp_localize_script(
			'my-recipes-blocks-build',
			'MyRecipesBlocksBlocksAjax',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'security'  => wp_create_nonce( self::NONCE_ACTION ),
			)
		);
		$relative_path = 'dist/blocks.editor.build.css';
		if ( file_exists( plugin_dir_path( __DIR__ ) . $relative_path ) && filesize( plugin_dir_path( __DIR__ ) . $relative_path ) > 0 ) {
			wp_enqueue_style(
				'my-recipes-blocks-editor-css',
				plugins_url( $relative_path, dirname( __FILE__ ) ),
				array( 'wp-edit-blocks' ),
				filemtime( plugin_dir_path( __DIR__ ) . $relative_path )
			);
		}
	}

	/**
	 * Server side whitelist blocks to be displayed in the inserter.
	 * The server side whitelist is the same list of the JS whitelist, but it also
	 * serves different blocks for different post types.
	 *
	 * IMPORTANT: add the complete name of the blocks created in this package,
	 * or you won't find them in the inserter!
	 *
	 * @param array   $allowed_block_types List passed by the filter.
	 * @param WP_Post $post The post.
	 *
	 * @return array
	 */
	public function allowed_block_types( $allowed_block_types, $post ) {
		$plugin_blocks = array(
			'post' => array(),
			'page' => array(),
		);
		// Under this line you don't need to modify.
		$blocks      = array();
		$core_blocks = get_option( 'mr-block-whitelist' );

		foreach ( $plugin_blocks as $post_type => $block_list ) {
			if ( isset( $core_blocks[ $post_type ] ) ) {
				$core_blocks_keys = array_keys( $core_blocks[ $post_type ] );

				$blocks[ $post_type ] = array_merge( $block_list, $core_blocks_keys );
			} else {
				$blocks[ $post_type ] = $block_list;
			}
		}

		if ( isset( $blocks[ $post->post_type ] ) ) {
			return $blocks[ $post->post_type ];
		}

		return $allowed_block_types;
	}

	/**
	 * Create a custom category for the blocks
	 *
	 * @param array   $categories The list of categories currently installed.
	 * @param WP_Post $post The post.
	 *
	 * @return array
	 */
	public function add_category( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'my-recipes',
					'title' => 'My Recipes Blocks',
				),
			)
		);
	}

	/**
	 * Adds a template to the post type "post"
	 */
	public function template_to_posts() {
		$post_type_object           = get_post_type_object( 'post' );
		$post_type_object->template = array(
			array( 'BLOCK_IDENTIFIER' ),

		);
		// $post_type_object->template_lock = 'all';
	}

	/**
	 * Add postmeta to the type "post"
	 */
	public function register_meta() {
		//$names_meta['BLOCK_CODE'] = 'META_KEY';
		//$this->register_meta_blocks( 'post', $names_meta );
	}

	/**
	 * Registers postmeta with Gutenberg compatibility and single value
	 *
	 * @param string $object_type Type of object to register meta on (like post).
	 * @param array  $names_meta List of meta.
	 */
	protected function register_meta_blocks( $object_type, $names_meta ) {
		foreach ( $names_meta as $code => $meta ) {
			register_meta(
				$object_type,
				$meta,
				array(
					'show_in_rest' => true,
					'single'       => true,
					'type'         => 'string',
				)
			);
		}
	}

	/**
	 * Registers a callback for a dynamic block
	 */
	public function register_callbacks() {
		// instantiate a new block and register the callback.
		// example.
		$block_01 = new My_Recipes_Blocks_Block_01();
		register_block_type(
			'mr-blocks/block-01',
			array(
				'render_callback' => array(
					$block_01,
					'render_block',
				),
			)
		);
	}


}

new My_Recipes_Blocks_Initializer();
