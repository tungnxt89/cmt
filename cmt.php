<?php
/**
 * Plugin Name: Chung Minh Thu
 * Plugin URI:
 * Description: Quan ly ho so tra the
 * Author: tungnx
 * Version: 1.0.0-beta-1
 * Author URI:
 * Requires at least: 5.8
 * Tested up to: 5.8
 * Requires PHP: 7.0
 * Text Domain: cmt
 * Domain Path: /languages/
 *
 * @package cmt
 */

class CMT {
	public static $instance;
	public static $URL_PLUGIN;
	public static $PATH_PLUGIN;

	public function __construct() {
		add_action( 'activated_plugin', array( $this, 'install_tables' ) );

		self::$URL_PLUGIN  = plugin_dir_url( __FILE__ );
		self::$PATH_PLUGIN = plugin_dir_path( __FILE__ );

		$this->includes();
		$this->install_tables();
		$this->init();
	}

	private function includes() {
		require_once 'inc/db.php';
		require_once 'inc/api/cmt-api.php';
		require_once 'inc/models/filter-base.php';
		require_once 'inc/models/filter_users.php';
	}

	public function install_tables() {
		CMT_DB::instance()->createTables();
	}

	public function init() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		CMT_API::instance();
	}

	public function menu() {
		add_menu_page(
			'CMT',
			'CMT',
			'administrator',
			'cmt',
			array( $this, 'output' ),
			'',
			10
		);
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( ! $screen || 'toplevel_page_cmt' !== $screen->id ) {
			return;
		}

		wp_register_script( 'papaparse', self::$URL_PLUGIN . 'assets/js/papaparse.min.js' );
		wp_enqueue_script( 'papaparse' );

		wp_register_script( 'barcode', self::$URL_PLUGIN . 'assets/js/barcode.js', [ 'jquery', 'wp-api-fetch' ], uniqid() );
		wp_enqueue_script( 'barcode' );

		wp_register_script( 'search', self::$URL_PLUGIN . 'assets/js/search.js', [ 'jquery', 'wp-api-fetch' ], uniqid() );
		wp_enqueue_script( 'search' );

		wp_register_script( 'main', self::$URL_PLUGIN . 'assets/js/main.js', [ 'jquery', 'wp-api-fetch' ], uniqid() );
		wp_enqueue_script( 'main' );

		wp_register_style( 'cmt', self::$URL_PLUGIN . 'assets/css/cmt.css', [], uniqid() );
		wp_enqueue_style( 'cmt' );
	}

	public function output() {
		require_once 'templates/dashboard.php';
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

CMT::instance();
