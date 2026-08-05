<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CRUD for the base materials list ("ECO-BASE PRICES").
 */
class PLSRP_Materials {

	public static function all( $search = '' ) {
		global $wpdb;
		$table = PLSRP_DB::materials_table();

		if ( $search ) {
			$like = '%' . $wpdb->esc_like( $search ) . '%';
			return $wpdb->get_results(
				$wpdb->prepare( "SELECT * FROM {$table} WHERE name LIKE %s ORDER BY name ASC", $like )
			);
		}

		return $wpdb->get_results( "SELECT * FROM {$table} ORDER BY name ASC" );
	}

	public static function get( $id ) {
		global $wpdb;
		$table = PLSRP_DB::materials_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ) );
	}

	/**
	 * Case-insensitive lookup by name. The source spreadsheet's VLOOKUPs are
	 * case-insensitive, so "Malinis na Tubig" and "malinis na tubig" must
	 * resolve to the same material here too, or the importer would create
	 * spurious near-duplicate materials with a price of 0.
	 */
	public static function get_by_name( $name ) {
		global $wpdb;
		$table = PLSRP_DB::materials_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE LOWER(name) = LOWER(%s)", trim( $name ) ) );
	}

	/**
	 * Insert, or update if a material with this name already exists.
	 * Used by both the admin form and the spreadsheet importer.
	 *
	 * $game_item_id is left as null by the importer (which has no idea what
	 * the in-game item id is) so an existing value is never clobbered; the
	 * admin form passes an explicit string (possibly empty) to update it.
	 */
	public static function upsert( $name, $base_price, $unit = '', $game_item_id = null ) {
		global $wpdb;
		$table = PLSRP_DB::materials_table();
		$name = trim( $name );

		$existing = self::get_by_name( $name );

		$data    = array(
			'base_price' => $base_price,
			'unit'       => $unit,
		);
		$formats = array( '%f', '%s' );
		if ( null !== $game_item_id ) {
			$data['game_item_id'] = trim( $game_item_id );
			$formats[]            = '%s';
		}

		if ( $existing ) {
			$wpdb->update( $table, $data, array( 'id' => $existing->id ), $formats, array( '%d' ) );
			return $existing->id;
		}

		$data['name'] = $name;
		$formats[]    = '%s';
		$wpdb->insert( $table, $data, $formats );

		return $wpdb->insert_id;
	}

	/**
	 * Sets just the game_item_id column, without touching price/unit. Used
	 * by the Crafting Tracker sync's auto-backfill.
	 */
	public static function set_game_item_id( $id, $game_item_id ) {
		global $wpdb;
		$table = PLSRP_DB::materials_table();
		return $wpdb->update(
			$table,
			array( 'game_item_id' => trim( (string) $game_item_id ) ),
			array( 'id' => $id ),
			array( '%s' ),
			array( '%d' )
		);
	}

	public static function delete( $id ) {
		global $wpdb;
		$table = PLSRP_DB::materials_table();

		// Guard: don't orphan recipes silently - refuse if in use.
		$ingredients = PLSRP_DB::ingredients_table();
		$in_use = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$ingredients} WHERE material_id = %d", $id ) );
		if ( $in_use > 0 ) {
			return new WP_Error( 'plrp_material_in_use', __( 'This material is used by one or more recipes and cannot be deleted. Remove it from those recipes first.', 'plrp-economy' ) );
		}

		return $wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) );
	}
}
