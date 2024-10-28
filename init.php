<?php
/**
 * Plugin Name: Accordion FAQ Builder
 * Plugin URI: https://devhelp.us
 * Description: This is an Accordion FAQ Builder plugin which helps you to create awesome, eye-catchy unlimited FAQ accordion.
 * Version: 0.3
 * Requires at least: 4.0
 * Requires PHP:      5.6
 * Author: autocircle
 *
 * Text Domain: a-faq-builder
 * Domain Path: /languages/
 *
 * @package a-faq-builder
 */

use \AFaqBuilder\Bootstrap;
use \AFaqBuilder\Autoloader;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'AFAQBUILDER_FILE' ) ) {
	define( 'AFAQBUILDER_FILE', __FILE__ );
}

if ( ! defined( 'AFAQBUILDER_BASENAME' ) ) {
	define( 'AFAQBUILDER_BASENAME', plugin_basename( AFAQBUILDER_FILE ) );
}

if ( ! defined( 'AFAQBUILDER_DIR' ) ) {
	define( 'AFAQBUILDER_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'AFAQBUILDER_URL' ) ) {
	define( 'AFAQBUILDER_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'AFAQBUILDER_NAMESPACE' ) ) {
	define( 'AFAQBUILDER_NAMESPACE', 'AFaqBuilder' );
}

if ( ! defined( 'AFAQBUILDER_VERSION' ) ) {
	define( 'AFAQBUILDER_VERSION', '0.3' );
}

// Run autoloader
require_once __DIR__ . '/autoloader.php';
Autoloader::run();

// Bootstrap the plugin
Bootstrap::instance();
