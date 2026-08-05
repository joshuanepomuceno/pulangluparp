<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CRUD for craftable items and their recipe (ingredient) lines.
 */
class PLSRP_Items {

	public static function all_for_profession( $profession_id ) {
		global $wpdb;
		$table = PLSRP_DB::items_table();
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE profession_id = %d ORDER BY sort_order ASC, name ASC", $profession_id )
		);
	}

	public static function all() {
		global $wpdb;
		$items_table = PLSRP_DB::items_table();
		$professions_table = PLSRP_DB::professions_table();
		return $wpdb->get_results(
			"SELECT i.*, p.name AS profession_name, p.slug AS profession_slug
			 FROM {$items_table} i
			 INNER JOIN {$professions_table} p ON p.id = i.profession_id
			 ORDER BY p.sort_order ASC, i.sort_order ASC, i.name ASC"
		);
	}

	public static function get( $id ) {
		global $wpdb;
		$table = PLSRP_DB::items_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ) );
	}

	public static function get_ingredients( $item_id ) {
		global $wpdb;
		$ingredients_table = PLSRP_DB::ingredients_table();
		$materials_table   = PLSRP_DB::materials_table();

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT ing.id, ing.item_id, ing.material_id, ing.quantity, ing.sort_order,
				        m.name AS material_name, m.base_price, m.unit
				 FROM {$ingredients_table} ing
				 INNER JOIN {$materials_table} m ON m.id = ing.material_id
				 WHERE ing.item_id = %d
				 ORDER BY ing.sort_order ASC, ing.id ASC",
				$item_id
			)
		);
	}

	/**
	 * Create or update an item and replace its ingredient lines wholesale.
	 * $ingredients is an array of ['material_id' => int, 'quantity' => float].
	 *
	 * $tracker_category / $game_item_id only matter for items that aren't
	 * already in the Crafting Tracker's recipes.json - they tell the sync
	 * what category to file a brand-new recipe under and what in-game item
	 * id it rewards, since Economy has no way to know either on its own.
	 * Passing null leaves an existing value alone on update.
	 */
	public static function save( $item_id, $profession_id, $name, $notes, $ingredients, $tracker_category = null, $game_item_id = null ) {
		global $wpdb;
		$items_table = PLSRP_DB::items_table();
		$ingredients_table = PLSRP_DB::ingredients_table();

		$data = array(
			'profession_id' => (int) $profession_id,
			'name'          => sanitize_text_field( $name ),
			'notes'         => sanitize_textarea_field( $notes ),
		);
		$formats = array( '%d', '%s', '%s' );

		if ( null !== $tracker_category ) {
			$data['tracker_category'] = sanitize_text_field( $tracker_category );
			$formats[]                = '%s';
		}
		if ( null !== $game_item_id ) {
			$data['game_item_id'] = sanitize_text_field( $game_item_id );
			$formats[]            = '%s';
		}

		if ( $item_id ) {
			$wpdb->update( $items_table, $data, array( 'id' => $item_id ), $formats, array( '%d' ) );
		} else {
			if ( ! isset( $data['tracker_category'] ) ) {
				$data['tracker_category'] = '';
				$formats[]                = '%s';
			}
			if ( ! isset( $data['game_item_id'] ) ) {
				$data['game_item_id'] = '';
				$formats[]            = '%s';
			}
			$wpdb->insert( $items_table, $data, $formats );
			$item_id = $wpdb->insert_id;
		}

		// Replace ingredient lines.
		$wpdb->delete( $ingredients_table, array( 'item_id' => $item_id ), array( '%d' ) );

		$order = 0;
		foreach ( $ingredients as $row ) {
			if ( empty( $row['material_id'] ) ) {
				continue;
			}
			$wpdb->insert(
				$ingredients_table,
				array(
					'item_id'     => $item_id,
					'material_id' => (int) $row['material_id'],
					'quantity'    => (float) $row['quantity'],
					'sort_order'  => $order,
				),
				array( '%d', '%d', '%f', '%d' )
			);
			$order++;
		}

		return $item_id;
	}

	public static function delete( $id ) {
		global $wpdb;
		$items_table = PLSRP_DB::items_table();
		$ingredients_table = PLSRP_DB::ingredients_table();

		$wpdb->delete( $ingredients_table, array( 'item_id' => $id ), array( '%d' ) );
		return $wpdb->delete( $items_table, array( 'id' => $id ), array( '%d' ) );
	}
}
