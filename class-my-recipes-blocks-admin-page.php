<?php
/**
 * The administration page
 *
 * @link       www.ermal.dev
 * @since      1.0.0
 *
 * @package    My_Recipes_Blocks
 */

/**
 * Class My_Recipes_Blocks_Admin_Page
 */
class My_Recipes_Blocks_Admin_Page {

	/**
	 * Holds the values to be used in the fields callbacks
	 *
	 * @var array
	 */
	private $options;
	/**
	 * Name of the option
	 *
	 * @var string
	 */
	const OPTION_NAME = 'mr-block-whitelist';

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'mr-blocks-manager';

	/**
	 * Administration page title
	 *
	 * @var string
	 */
	const PAGE_TITLE = 'Blocks Manager';

	/**
	 * Start up
	 */
	public function __construct() {

	}

	/**
	 * Init the hooks
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_script' ) );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_admin_script() {
		wp_enqueue_script(
			self::PAGE_SLUG . '-admin',
			plugin_dir_url( __FILE__ ) . 'js/admin_scripts.js',
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'js/admin_scripts.js' ),
			true
		);
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_menu_page(
			self::PAGE_TITLE,
			self::PAGE_TITLE,
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'create_admin_page' ),
			'dashicons-schedule',
			31
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		$this->options = get_option( self::OPTION_NAME );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( self::PAGE_TITLE ); ?></h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields.
				settings_fields( self::PAGE_SLUG . '-group' );
				do_settings_sections( self::PAGE_SLUG . '-admin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			self::PAGE_SLUG . '-group', // Option group.
			self::OPTION_NAME, // Option name.
			array( $this, 'sanitize' ) // Sanitize.
		);

		add_settings_section(
			'setting_section_id', // ID.
			'', // Title.
			array( $this, 'print_section_info' ), // Callback.
			self::PAGE_SLUG . '-admin' // Page.
		);

		add_settings_field(
			'size', // ID.
			'Blocchi core da mostrare', // Title.
			array( $this, 'whitelist_blocks_callback' ), // Callback.
			self::PAGE_SLUG . '-admin', // Page.
			'setting_section_id' // Section.
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys.
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		return $input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print 'Scegliere i blocchi da rendere disponibili';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function whitelist_blocks_callback() {
		$post_types = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			)
		);
		$html       = '<table><tr>';

		$html .= '<th>Titolo blocco</th>';
		foreach ( $post_types as $key => $post_type ) {
			$html .= '<th>' . $post_type . '</th>';
		}
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>Seleziona tutti i blocchi</td>';
		foreach ( $post_types as $key => $post_type ) {
			$html .= '<td><input type="checkbox" name="check-all" class="check-all" data-type="' . $post_type . '" /></td>';
		}
		$html .= '</tr>';
		foreach ( My_Recipes_Blocks_Core_Blocks::ALL as $slug => $title ) {
			$html .= '<tr>';
			$html .= '<td';
			if ( in_array( $slug, My_Recipes_Blocks_Core_Blocks::MANDATORY, true ) ) {
				$html .= ' style="color: red;" title="Questo blocco è meglio non escluderlo"';
			}
			$html .= '>' . $title . '</td>';
			foreach ( $post_types as $key => $post_type ) {
				$html .= '<td><input type="checkbox" name="' . self::OPTION_NAME . '[' . $post_type . ']' . '[' . $slug . ']" value="on" class="checkbox" data-class="' . $post_type . '"';
				if ( isset( $this->options[ $post_type ][ $slug ] ) ) {
					$html .= checked( $this->options[ $post_type ][ $slug ], 'on', false );
				}
				$html .= ' /></td>';

			}
			$html .= '</tr>';
		}

		$html .= '</table>';
		echo $html;
	}

}

( new My_Recipes_Blocks_Admin_Page() )->init();
