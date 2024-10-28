<?php
/**
 * Bootstrap
 *
 * @since      0.1
 *
 * @package    a-faq-builder
 */

namespace AFaqBuilder;

use \AFaqBuilder\Includes\Register_Meta_Boxes;
use \AFaqBuilder\Includes\Register_Post_Type;
use \AFaqBuilder\Includes\Shortcode;
use \AFaqBuilder\Includes\Enqueue_Script;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bootstrap {

	/**
	 * Holds class object
	 *
	 * @var   object
	 * @since 0.1
	 */
	private static $instance;

	public function __construct() {
		Register_Post_Type::instance();
		Register_Meta_Boxes::instance();
		Shortcode::instance();
		Enqueue_Script::instance();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
