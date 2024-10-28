<?php
/**
 * Helper Class
 *
 * @since      0.1
 *
 * @package    a-faq-builder
 */

namespace AFaqBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Helper {

	public static $defaults = array(
		'id' => 0,
		'position' => 'vertical',
		'type' => 'content',
		'template' => '1',
		'bullet_type' => 'icon',
	);

	public static function get_all_templates() {

		$template_list = array(
			1 => __( 'Template 1', 'a-faq-builder' ),
			2 => __( 'Template 2', 'a-faq-builder' ),
			3 => __( 'Template 3', 'a-faq-builder' ),
			4 => __( 'Template 4', 'a-faq-builder' ),
			5 => __( 'Template 5', 'a-faq-builder' ),
			6 => __( 'Template 6', 'a-faq-builder' ),
			7 => __( 'Template 7', 'a-faq-builder' ),
			8 => __( 'Template 8', 'a-faq-builder' ),
			9 => __( 'Template 9', 'a-faq-builder' ),
		);

		return apply_filters( 'afb_template_list', $template_list );
	}

	/**
	 * Recursive sanitation for an array
	 *
	 * @link https://wordpress.stackexchange.com/a/255238
	 * @param $array
	 *
	 * @return mixed
	 */
	public static function recursive_sanitize_text_field( $array ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = self::recursive_sanitize_text_field( $value );
			}
			else {
				$value = sanitize_text_field( $value );
			}
		}

		return $array;
	}
}
