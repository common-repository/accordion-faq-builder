<?php
/**
 * Autoloader
 *
 * @since      0.1
 *
 * @package    a-faq-builder
 */

namespace AFaqBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Autoloader {
	/**
	 * Run autoloader.
	 */
	public static function run() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exist and load it.
	 *
	 * @param $class
	 */
	private static function autoload( $class ) {
		if ( ! class_exists( $class ) ) {
			self::load_class( $class );
		}
	}

	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @param $relative_class_name
	 */
	private static function load_class( $relative_class_name ) {

		$relative_class_name = str_replace( AFAQBUILDER_NAMESPACE . '\\', '', $relative_class_name );
		
		$filename = strtolower(
			preg_replace(
				[ '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$relative_class_name
				)
			);
		
		$filename = AFAQBUILDER_DIR . $filename . '.php';
		if ( is_readable( $filename ) ) {
			require_once $filename;
		}
	}
}
