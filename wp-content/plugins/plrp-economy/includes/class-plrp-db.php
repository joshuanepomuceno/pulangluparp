<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Table names + schema installation.
 *
 * Four tables:
 *  - materials:        the "ECO-BASE PRICES" master list (raw material -> unit price)
 *  - professions:      one row per sheet tab (Pagkain, Panday, Armero, ...) holding
 *                       the internal markup % plus optional SRP publish settings that
 *                       mirror the "ES-AR-PEE" buffer/margin/round-up layer.
 *  - items:            one row per craftable item, belongs to a profession.
 *  - item_ingredients:  the recipe lines for an item (material + quantity).
 *
 * Costs and selling prices are never stored - they're computed on the fly by
 * PLSRP_Calculator so that editing a base material price or a markup % updates
 * every recipe that depends on it immediately.
 */
class PLSRP_DB {

	public static function materials_table() {
		global $wpdb;
		return $wpdb->prefix . 'plsrp_materials';
	}

	public static function professions_table() {
		global $wpdb;
		return $wpdb->prefix . 'plsrp_professions';
	}

	public static function items_table() {
		global $wpdb;
		return $wpdb->prefix . 'plsrp_items';
	}

	public static function ingredients_table() {
		global $wpdb;
		return $wpdb->prefix . 'plsrp_item_ingredients';
	}

	public static function install() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		$materials = self::materials_table();
		$professions = self::professions_table();
		$items = self::items_table();
		$ingredients = self::ingredients_table();

		$sql = array();

		$sql[] = "CREATE TABLE {$materials} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(191) NOT NULL,
			base_price DECIMAL(12,4) NOT NULL DEFAULT 0,
			unit VARCHAR(50) NOT NULL DEFAULT '',
			game_item_id VARCHAR(191) NOT NULL DEFAULT '',
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY name (name)
		) {$charset_collate};";

		$sql[] = "CREATE TABLE {$professions} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(191) NOT NULL,
			slug VARCHAR(191) NOT NULL,
			markup_pct DECIMAL(6,4) NOT NULL DEFAULT 0,
			srp_enabled TINYINT(1) NOT NULL DEFAULT 0,
			srp_buffer_pct DECIMAL(6,4) NOT NULL DEFAULT 0.15,
			srp_margin_pct DECIMAL(6,4) NOT NULL DEFAULT 0.25,
			srp_rounding_increment DECIMAL(6,4) NOT NULL DEFAULT 0.1,
			sort_order INT NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug)
		) {$charset_collate};";

		$sql[] = "CREATE TABLE {$items} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			profession_id BIGINT UNSIGNED NOT NULL,
			name VARCHAR(191) NOT NULL,
			notes TEXT NULL,
			tracker_category VARCHAR(100) NOT NULL DEFAULT '',
			game_item_id VARCHAR(191) NOT NULL DEFAULT '',
			sort_order INT NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY profession_id (profession_id)
		) {$charset_collate};";

		$sql[] = "CREATE TABLE {$ingredients} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			item_id BIGINT UNSIGNED NOT NULL,
			material_id BIGINT UNSIGNED NOT NULL,
			quantity DECIMAL(12,4) NOT NULL DEFAULT 0,
			sort_order INT NOT NULL DEFAULT 0,
			PRIMARY KEY  (id),
			KEY item_id (item_id),
			KEY material_id (material_id)
		) {$charset_collate};";

		foreach ( $sql as $statement ) {
			dbDelta( $statement );
		}
	}
}
