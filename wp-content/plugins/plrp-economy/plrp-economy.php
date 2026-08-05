<?php
/**
 * Plugin Name: PLRP Economy (SRP Price List)
 * Plugin URI:  https://pulanglupa.rp
 * Description: Manages Pulang Lupa RP's crafting economy - base material prices, per-profession recipes/markups, and a public SRP price list shortcode. Recreates the logic from the "REVISED_PLRP_SRP_WORKING SHEET" spreadsheet as an editable, live-recalculating dataset.
 * Version:     1.1.0
 * Author:      Pulang Lupa RP
 * Text Domain: plrp-economy
 * License:     GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'PLSRP_VERSION', '1.1.0' );
define( 'PLSRP_FILE', __FILE__ );
define( 'PLSRP_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLSRP_URL', plugin_dir_url( __FILE__ ) );
define( 'PLSRP_DB_VERSION', '1.1.0' );

/**
 * Autoload our includes. Kept simple (no composer) on purpose so this
 * drops into any WordPress install without a build step.
 */
require_once PLSRP_DIR . 'includes/class-plrp-db.php';
require_once PLSRP_DIR . 'includes/class-plrp-materials.php';
require_once PLSRP_DIR . 'includes/class-plrp-professions.php';
require_once PLSRP_DIR . 'includes/class-plrp-items.php';
require_once PLSRP_DIR . 'includes/class-plrp-calculator.php';
require_once PLSRP_DIR . 'includes/class-plrp-importer.php';
require_once PLSRP_DIR . 'includes/class-plrp-tracker-sync.php';
require_once PLSRP_DIR . 'includes/class-plrp-admin.php';
require_once PLSRP_DIR . 'includes/class-plrp-shortcode.php';

/**
 * Activation: create/upgrade tables.
 */
function plsrp_activate() {
	PLSRP_DB::install();
	update_option( 'plsrp_db_version', PLSRP_DB_VERSION );
}
register_activation_hook( __FILE__, 'plsrp_activate' );

/**
 * Keep tables in sync if the plugin is updated with a schema change,
 * without requiring the user to deactivate/reactivate.
 */
function plsrp_maybe_upgrade() {
	if ( get_option( 'plsrp_db_version' ) !== PLSRP_DB_VERSION ) {
		PLSRP_DB::install();
		update_option( 'plsrp_db_version', PLSRP_DB_VERSION );
	}
}
add_action( 'plugins_loaded', 'plsrp_maybe_upgrade' );

/**
 * Bootstrap admin + public facing pieces.
 */
function plsrp_init() {
	load_plugin_textdomain( 'plrp-economy', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	if ( is_admin() ) {
		new PLSRP_Admin();
	}

	new PLSRP_Shortcode();
}
add_action( 'plugins_loaded', 'plsrp_init' );

/**
 * Enqueue public assets only when the shortcode is actually used on the page.
 */
function plsrp_public_assets() {
	wp_register_style( 'plrp-economy-public', PLSRP_URL . 'assets/public.css', array(), PLSRP_VERSION );
	wp_register_script( 'plrp-economy-public', PLSRP_URL . 'assets/public.js', array(), PLSRP_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'plsrp_public_assets' );

/**
 * Admin-only assets.
 */
function plsrp_admin_assets( $hook ) {
	if ( strpos( $hook, 'plrp-economy' ) === false ) {
		return;
	}
	wp_enqueue_style( 'plrp-economy-admin', PLSRP_URL . 'assets/admin.css', array(), PLSRP_VERSION );
	wp_enqueue_script( 'plrp-economy-admin', PLSRP_URL . 'assets/admin.js', array( 'jquery' ), PLSRP_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'plsrp_admin_assets' );
