<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recreates the spreadsheet's formulas in PHP so prices always reflect the
 * current base material prices and markup settings.
 *
 * Per-ingredient (mirrors columns E/D*C in the sheet):
 *   line_cost = quantity * material.base_price
 *
 * Per-item (mirrors columns F/G/H):
 *   total_mat_cost = SUM(line_cost for all ingredients of the item)
 *   markup         = total_mat_cost * profession.markup_pct
 *   selling_price  = total_mat_cost + markup   <- "internal" selling price
 *
 * Optional SRP publish layer (mirrors the "ES-AR-PEE" sheet):
 *   buffered       = total_mat_cost * (1 + srp_buffer_pct)   // sheet used 15%
 *   margin         = buffered * srp_margin_pct               // sheet used 25%
 *   srp            = buffered + margin
 *   srp_rounded    = round UP to the nearest srp_rounding_increment (sheet used 0.1)
 *
 * When a profession has srp_enabled turned on, the public price shown is
 * srp_rounded; otherwise it's the internal selling_price.
 */
class PLSRP_Calculator {

	/**
	 * Full cost/price breakdown for a single item.
	 *
	 * @param object $item        Row from PLSRP_Items::get().
	 * @param object $profession  Row from PLSRP_Professions::get().
	 * @param array  $ingredients Rows from PLSRP_Items::get_ingredients() (already joined to materials).
	 * @return array
	 */
	public static function breakdown_for_item( $item, $profession, $ingredients ) {
		$lines = array();
		$total_mat_cost = 0.0;

		foreach ( $ingredients as $ingredient ) {
			$line_cost = (float) $ingredient->quantity * (float) $ingredient->base_price;
			$total_mat_cost += $line_cost;

			$lines[] = array(
				'material_id'   => (int) $ingredient->material_id,
				'material_name' => $ingredient->material_name,
				'quantity'      => (float) $ingredient->quantity,
				'unit_price'    => (float) $ingredient->base_price,
				'line_cost'     => $line_cost,
			);
		}

		$markup_pct    = $profession ? (float) $profession->markup_pct : 0.0;
		$markup        = $total_mat_cost * $markup_pct;
		$selling_price = $total_mat_cost + $markup;

		$srp = null;
		if ( $profession && ! empty( $profession->srp_enabled ) ) {
			$buffer_pct = (float) $profession->srp_buffer_pct;
			$margin_pct = (float) $profession->srp_margin_pct;
			$increment  = (float) $profession->srp_rounding_increment;

			$buffered = $total_mat_cost * ( 1 + $buffer_pct );
			$margin   = $buffered * $margin_pct;
			$srp_raw  = $buffered + $margin;
			$srp      = $increment > 0 ? self::round_up_to_increment( $srp_raw, $increment ) : $srp_raw;
		}

		return array(
			'item_id'          => $item->id,
			'item_name'        => $item->name,
			'ingredients'      => $lines,
			'total_mat_cost'   => $total_mat_cost,
			'markup_pct'       => $markup_pct,
			'markup'           => $markup,
			'selling_price'    => $selling_price,
			'srp_enabled'      => $profession ? (bool) $profession->srp_enabled : false,
			'srp'              => $srp,
			// The price to actually display to players: the polished SRP if
			// the profession publishes one, otherwise the internal price.
			'display_price'    => null !== $srp ? $srp : $selling_price,
		);
	}

	/**
	 * Convenience wrapper: computes the breakdown for an item id, fetching
	 * its ingredients and profession along the way.
	 */
	public static function breakdown_for_item_id( $item_id ) {
		$item = PLSRP_Items::get( $item_id );
		if ( ! $item ) {
			return null;
		}
		$profession  = PLSRP_Professions::get( $item->profession_id );
		$ingredients = PLSRP_Items::get_ingredients( $item_id );

		return self::breakdown_for_item( $item, $profession, $ingredients );
	}

	/**
	 * Mirrors Excel's ROUNDUP(value, -log10(increment)) for increments like 0.1.
	 */
	public static function round_up_to_increment( $value, $increment ) {
		if ( $increment <= 0 ) {
			return $value;
		}
		return ceil( round( $value / $increment, 6 ) ) * $increment;
	}
}
