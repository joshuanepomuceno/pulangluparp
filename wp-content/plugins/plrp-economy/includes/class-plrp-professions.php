<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CRUD for professions (the sheet tabs: Pagkain, Panday, Armero, etc.)
 */
class PLSRP_Professions {

	public static function all() {
		global $wpdb;
		$table = PLSRP_DB::professions_table();
		return $wpdb->get_results( "SELECT * FROM {$table} ORDER BY sort_order ASC, name ASC" );
	}

	public static function get( $id ) {
		global $wpdb;
		$table = PLSRP_DB::professions_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", $id ) );
	}

	public static function get_by_slug( $slug ) {
		global $wpdb;
		$table = PLSRP_DB::professions_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE slug = %s", $slug ) );
	}

	public static function upsert_by_name( $name, $markup_pct = 0, $args = array() ) {
		global $wpdb;
		$table = PLSRP_DB::professions_table();
		$slug  = sanitize_title( $name );

		$defaults = array(
			'srp_enabled'            => 0,
			'srp_buffer_pct'         => 0.15,
			'srp_margin_pct'         => 0.25,
			'srp_rounding_increment' => 0.1,
			'sort_order'             => 0,
		);
		$args = wp_parse_args( $args, $defaults );

		$existing = self::get_by_slug( $slug );

		$data = array(
			'name'                   => $name,
			'slug'                   => $slug,
			'markup_pct'             => $markup_pct,
			'srp_enabled'            => (int) $args['srp_enabled'],
			'srp_buffer_pct'         => $args['srp_buffer_pct'],
			'srp_margin_pct'         => $args['srp_margin_pct'],
			'srp_rounding_increment' => $args['srp_rounding_increment'],
			'sort_order'             => $args['sort_order'],
		);
		$formats = array( '%s', '%s', '%f', '%d', '%f', '%f', '%f', '%d' );

		if ( $existing ) {
			$wpdb->update( $table, $data, array( 'id' => $existing->id ), $formats, array( '%d' ) );
			return $existing->id;
		}

		$wpdb->insert( $table, $data, $formats );
		return $wpdb->insert_id;
	}

	public static function save( $id, $fields ) {
		global $wpdb;
		$table = PLSRP_DB::professions_table();

		$data = array(
			'name'                   => sanitize_text_field( $fields['name'] ),
			'markup_pct'             => (float) $fields['markup_pct'],
			'srp_enabled'            => ! empty( $fields['srp_enabled'] ) ? 1 : 0,
			'srp_buffer_pct'         => (float) $fields['srp_buffer_pct'],
			'srp_margin_pct'         => (float) $fields['srp_margin_pct'],
			'srp_rounding_increment' => (float) $fields['srp_rounding_increment'],
		);

		if ( $id ) {
			return $wpdb->update( $table, $data, array( 'id' => $id ), array( '%s', '%f', '%d', '%f', '%f', '%f' ), array( '%d' ) );
		}

		$data['slug'] = sanitize_title( $data['name'] );
		return $wpdb->insert( $table, $data, array( '%s', '%f', '%d', '%f', '%f', '%f', '%s' ) );
	}

	public static function delete( $id ) {
		global $wpdb;

		$items_table = PLSRP_DB::items_table();
		$in_use = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$items_table} WHERE profession_id = %d", $id ) );
		if ( $in_use > 0 ) {
			return new WP_Error( 'plrp_profession_in_use', __( 'This profession still has items assigned to it. Move or delete those items first.', 'plrp-economy' ) );
		}

		$table = PLSRP_DB::professions_table();
		return $wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) );
	}
}
