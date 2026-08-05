<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reconciles PLRP Economy's items/ingredients with the Pulang Lupa Crafting
 * Tracker's data/recipes.json, so staff only maintain recipes once (here)
 * and the in-game crafting checklist stays current.
 *
 * IMPORTANT - this is a MERGE, not a mirror:
 *   recipes.json contains a lot Economy has no concept of at all (ammo,
 *   weapons, tools, smelted materials - anything without an SRP price), and
 *   every recipe there also carries a "category" (job + sub-group, drives
 *   the tracker's job-gated visibility) and a "reward" in-game item id that
 *   Economy simply doesn't have data for.
 *
 * So the sync only ever:
 *   - UPDATES the ingredient list of a recipe that already exists in the
 *     tracker (matched by item name), leaving its category/reward/desc
 *     alone - those belong to the tracker's domain, not Economy's.
 *   - ADDS a brand-new recipe only when the admin has explicitly filled in
 *     that item's Tracker Category and Game Item ID fields (see the Item
 *     edit screen) - never guessed, since a wrong in-game id would silently
 *     break crafting for players.
 *   - NEVER removes a recipe. An item disappearing from Economy doesn't
 *     mean it stopped being craftable in-game.
 *
 * Ingredient game ids are resolved from whatever the tracker's existing
 * recipes already reveal (any ingredient/reward label -> id pairing found
 * anywhere in the file), falling back to a material's own game_item_id if
 * one has been set by hand. If neither is known, that item is skipped
 * rather than written with a guessed/missing id.
 */
class PLSRP_Tracker_Sync {

	const PLUGIN_SLUG        = 'pl-crafting-tracker';
	const RELATIVE_JSON_PATH = 'data/recipes.json';

	public static function recipes_path() {
		return WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG . '/' . self::RELATIVE_JSON_PATH;
	}

	public static function is_available() {
		return file_exists( self::recipes_path() ) && is_writable( self::recipes_path() );
	}

	private static function load_recipes() {
		$path = self::recipes_path();
		if ( ! file_exists( $path ) ) {
			return null;
		}
		$raw  = file_get_contents( $path );
		$data = json_decode( $raw, true );
		return is_array( $data ) ? $data : null;
	}

	/**
	 * Atomic write (write to a temp file, then rename over the original) plus
	 * a once-per-day backup, since this touches a live, player-facing file.
	 */
	private static function save_recipes( $recipes ) {
		$path = self::recipes_path();
		self::maybe_backup( $path );

		$json = wp_json_encode( $recipes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		if ( false === $json ) {
			return false;
		}

		$tmp = $path . '.tmp-' . wp_generate_password( 8, false, false );
		if ( false === file_put_contents( $tmp, $json, LOCK_EX ) ) {
			return false;
		}

		return rename( $tmp, $path );
	}

	private static function maybe_backup( $path ) {
		if ( ! file_exists( $path ) ) {
			return;
		}
		$backup = $path . '.backup-' . gmdate( 'Y-m-d' ) . '.json';
		if ( ! file_exists( $backup ) ) {
			@copy( $path, $backup ); // phpcs:ignore -- best-effort safety net, not fatal if it fails.
		}
	}

	private static function label_key( $label ) {
		return strtolower( trim( (string) $label ) );
	}

	/**
	 * label -> in-game item id, built from every ingredient/reward entry
	 * already in the file. This is how a material Economy has never been
	 * told the "real" id for still resolves correctly, as long as it's used
	 * somewhere in the tracker already.
	 */
	private static function build_label_map( $recipes ) {
		$map = array();
		foreach ( $recipes as $recipe ) {
			if ( ! empty( $recipe['reward'] ) && is_array( $recipe['reward'] ) ) {
				foreach ( $recipe['reward'] as $reward ) {
					if ( ! empty( $reward['label'] ) && ! empty( $reward['name'] ) ) {
						$map[ self::label_key( $reward['label'] ) ] = $reward['name'];
					}
				}
			}
			if ( ! empty( $recipe['ingredients'] ) && is_array( $recipe['ingredients'] ) ) {
				foreach ( $recipe['ingredients'] as $ing ) {
					if ( ! empty( $ing['label'] ) && ! empty( $ing['name'] ) ) {
						$map[ self::label_key( $ing['label'] ) ] = $ing['name'];
					}
				}
			}
		}
		return $map;
	}

	private static function build_name_index( $recipes ) {
		$index = array();
		foreach ( $recipes as $i => $recipe ) {
			if ( ! empty( $recipe['name'] ) ) {
				$index[ self::label_key( $recipe['name'] ) ] = $i;
			}
		}
		return $index;
	}

	/**
	 * Whether an item with this name already exists as a recipe in the
	 * tracker - used by the Item edit screen to explain whether the Tracker
	 * Category / Game Item ID fields are needed (only for items not already
	 * matched) or ignored (matched items keep their existing category/reward).
	 */
	public static function is_recipe_present( $item_name ) {
		$recipes = self::load_recipes();
		if ( ! $recipes ) {
			return false;
		}
		return isset( self::build_name_index( $recipes )[ self::label_key( $item_name ) ] );
	}

	/** Distinct categories already used in the tracker, for the admin's datalist. */
	public static function known_categories() {
		$recipes = self::load_recipes();
		if ( ! $recipes ) {
			return array();
		}
		$cats = array();
		foreach ( $recipes as $r ) {
			if ( ! empty( $r['category'] ) ) {
				$cats[ $r['category'] ] = true;
			}
		}
		$cats = array_keys( $cats );
		sort( $cats );
		return $cats;
	}

	/**
	 * Fills in Economy materials' game_item_id wherever it's still blank and
	 * the tracker already reveals it. Never overwrites a value that's already
	 * set (so a manual correction never gets clobbered by a re-sync).
	 */
	private static function backfill_material_game_ids( $label_map ) {
		$filled = 0;
		foreach ( PLSRP_Materials::all() as $material ) {
			if ( '' !== trim( (string) $material->game_item_id ) ) {
				continue;
			}
			$key = self::label_key( $material->name );
			if ( isset( $label_map[ $key ] ) ) {
				PLSRP_Materials::set_game_item_id( $material->id, $label_map[ $key ] );
				$filled++;
			}
		}
		return $filled;
	}

	/**
	 * Runs the full reconciliation. Safe to call repeatedly (idempotent when
	 * nothing has changed on the Economy side).
	 *
	 * @return array{available:bool,updated:array,added:array,skipped:array,backfilled_materials:int}
	 */
	public static function sync_all() {
		$report = array(
			'available'            => false,
			'updated'              => array(),
			'added'                => array(),
			'skipped'              => array(),
			'backfilled_materials' => 0,
		);

		if ( ! file_exists( self::recipes_path() ) ) {
			return $report; // Crafting Tracker plugin not installed/active - nothing to do.
		}

		$recipes = self::load_recipes();
		if ( null === $recipes ) {
			return $report; // File exists but isn't valid JSON - don't touch it.
		}
		$report['available'] = true;

		$label_map                       = self::build_label_map( $recipes );
		$report['backfilled_materials']  = self::backfill_material_game_ids( $label_map );

		$materials_by_id = array();
		foreach ( PLSRP_Materials::all() as $m ) {
			$materials_by_id[ $m->id ] = $m;
		}

		$name_index = self::build_name_index( $recipes );
		$changed    = false;

		foreach ( PLSRP_Items::all() as $item ) {
			$ingredients = PLSRP_Items::get_ingredients( $item->id );

			$tracker_ingredients = array();
			$unresolved          = array();

			foreach ( $ingredients as $ing ) {
				$key     = self::label_key( $ing->material_name );
				$game_id = isset( $label_map[ $key ] )
					? $label_map[ $key ]
					: ( isset( $materials_by_id[ $ing->material_id ] ) ? $materials_by_id[ $ing->material_id ]->game_item_id : '' );

				if ( '' === trim( (string) $game_id ) ) {
					$unresolved[] = $ing->material_name;
					continue;
				}

				$tracker_ingredients[] = array(
					'name'  => $game_id,
					'label' => $ing->material_name,
					'count' => (float) $ing->quantity,
				);
			}

			$name_key = self::label_key( $item->name );

			if ( isset( $name_index[ $name_key ] ) ) {
				if ( ! empty( $unresolved ) ) {
					$report['skipped'][] = array(
						'item'   => $item->name,
						'reason' => sprintf( 'Could not update - unknown game item id for: %s', implode( ', ', array_unique( $unresolved ) ) ),
					);
					continue;
				}

				$idx                             = $name_index[ $name_key ];
				$recipes[ $idx ]['ingredients']   = $tracker_ingredients;
				$report['updated'][]              = $item->name;
				$changed                          = true;
				continue;
			}

			// Not in the tracker yet - only add it if the admin explicitly
			// set both a category and a game item id on this item.
			$category  = trim( (string) $item->tracker_category );
			$reward_id = trim( (string) $item->game_item_id );

			if ( '' === $category || '' === $reward_id ) {
				$report['skipped'][] = array(
					'item'   => $item->name,
					'reason' => 'New item - set a Tracker Category and Game Item ID on it (Items screen) to add it to the Crafting Tracker.',
				);
				continue;
			}
			if ( ! empty( $unresolved ) ) {
				$report['skipped'][] = array(
					'item'   => $item->name,
					'reason' => sprintf( 'Could not add - unknown game item id for: %s', implode( ', ', array_unique( $unresolved ) ) ),
				);
				continue;
			}

			$recipes[] = array(
				'name'        => $item->name,
				'category'    => $category,
				'ingredients' => $tracker_ingredients,
				'reward'      => array( array( 'name' => $reward_id, 'label' => $reward_id, 'count' => 1 ) ),
				'desc'        => $item->notes ? $item->notes : null,
			);
			$name_index[ $name_key ] = count( $recipes ) - 1;
			$report['added'][]      = $item->name;
			$changed                = true;
		}

		if ( $changed ) {
			self::save_recipes( $recipes );
		}

		return $report;
	}
}
