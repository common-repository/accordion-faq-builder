<?php
/**
 * Enqueue style and scripts
 *
 * @since      0.1
 *
 * @package    a-faq-builder
 */

namespace AFaqBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Enqueue_Script {

	private static $instance;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 0.1
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}
		return self::$instance;
	}

	protected function setup() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'public_scripts' ] );
	}

	public function admin_scripts() {
		wp_enqueue_style( 'afb_admin_style', trailingslashit( AFAQBUILDER_URL ) . 'assets/css/admin-style.css', array(), AFAQBUILDER_VERSION, 'all' );
		wp_enqueue_script( 'shortable', trailingslashit( AFAQBUILDER_URL ) . 'assets/js/Sortable.js', array(), '1.15.0', true );
		wp_enqueue_script( 'afb_admin_script', trailingslashit( AFAQBUILDER_URL ) . 'assets/js/admin-script.js', array( 'shortable' ), AFAQBUILDER_VERSION, true );
		$data = array(
			'new_item_text' => esc_html__( 'New item', 'a-faq-builder' ),
			'copy_text'     => esc_html__( 'Shortcode has been copied.', 'a-faq-builder' ),
		);

		wp_localize_script( 'afb_admin_script', 'AFB_Admin_DATA', $data );
	}
	
	public function public_scripts() {
		wp_enqueue_style( 'fontawesome', trailingslashit( AFAQBUILDER_URL ) .'assets/css/fontawesome/css/all.min.css', array(), '6.1.1', 'all' );
		wp_enqueue_style( 'afb_style', trailingslashit( AFAQBUILDER_URL ) . 'assets/css/style.css', array(), AFAQBUILDER_VERSION, 'all' );
		wp_enqueue_script( 'afb_script', trailingslashit( AFAQBUILDER_URL ) . 'assets/js/script.js', array(), AFAQBUILDER_VERSION, true );

		$data = array(
			'multi_open' => false,
		);

		wp_localize_script( 'afb_script', 'AFB_DATA', $data );
	}
}
