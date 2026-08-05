<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var object|null $item */
/** @var array $ingredients */
/** @var array $professions */
/** @var array $materials */
/** @var array|null $breakdown */
/** @var bool $tracker_available */
/** @var array $tracker_categories */
/** @var bool $tracker_matched */

$is_edit = ! empty( $item );
?>
<div class="wrap plrp-admin">
	<h1><?php echo $is_edit ? esc_html__( 'Edit Item', 'plrp-economy' ) : esc_html__( 'Add Item', 'plrp-economy' ); ?></h1>

	<form method="post" id="plrp-item-form">
		<?php wp_nonce_field( 'plrp_save_item' ); ?>
		<input type="hidden" name="plrp_action" value="save_item" />
		<?php if ( $is_edit ) : ?>
			<input type="hidden" name="item_id" value="<?php echo esc_attr( $item->id ); ?>" />
		<?php endif; ?>

		<table class="form-table">
			<tr>
				<th><label for="plrp-name"><?php esc_html_e( 'Item Name', 'plrp-economy' ); ?></label></th>
				<td><input type="text" id="plrp-name" name="name" class="regular-text" value="<?php echo $is_edit ? esc_attr( $item->name ) : ''; ?>" required /></td>
			</tr>
			<tr>
				<th><label for="plrp-profession"><?php esc_html_e( 'Profession', 'plrp-economy' ); ?></label></th>
				<td>
					<select id="plrp-profession" name="profession_id" required>
						<option value=""><?php esc_html_e( '— Select —', 'plrp-economy' ); ?></option>
						<?php foreach ( $professions as $p ) : ?>
							<option value="<?php echo esc_attr( $p->id ); ?>" <?php selected( $is_edit && (int) $item->profession_id === (int) $p->id ); ?>>
								<?php echo esc_html( $p->name ); ?> (<?php echo esc_html( round( $p->markup_pct * 100, 2 ) ); ?>% markup<?php echo $p->srp_enabled ? ', SRP on' : ''; ?>)
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="plrp-notes"><?php esc_html_e( 'Notes', 'plrp-economy' ); ?></label></th>
				<td><textarea id="plrp-notes" name="notes" class="large-text" rows="2"><?php echo $is_edit ? esc_textarea( $item->notes ) : ''; ?></textarea></td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Recipe (Ingredients)', 'plrp-economy' ); ?></h2>

		<table class="widefat" id="plrp-ingredients-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Material', 'plrp-economy' ); ?></th>
					<th><?php esc_html_e( 'Quantity', 'plrp-economy' ); ?></th>
					<th><?php esc_html_e( 'Base Price', 'plrp-economy' ); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody id="plrp-ingredients-body">
				<?php
				$rows = $ingredients;
				if ( empty( $rows ) ) {
					$rows = array( (object) array( 'material_id' => '', 'quantity' => '' ) );
				}
				foreach ( $rows as $i => $row ) :
					?>
					<tr class="plrp-ingredient-row">
						<td>
							<select name="ingredient_material[]">
								<option value=""><?php esc_html_e( '— Select material —', 'plrp-economy' ); ?></option>
								<?php foreach ( $materials as $m ) : ?>
									<option value="<?php echo esc_attr( $m->id ); ?>" data-price="<?php echo esc_attr( $m->base_price ); ?>" <?php selected( ! empty( $row->material_id ) && (int) $row->material_id === (int) $m->id ); ?>>
										<?php echo esc_html( $m->name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
						<td><input type="number" step="0.0001" min="0" name="ingredient_quantity[]" value="<?php echo esc_attr( $row->quantity ); ?>" /></td>
						<td class="plrp-line-price"><?php echo isset( $row->base_price ) ? esc_html( number_format( (float) $row->base_price, 4 ) ) : ''; ?></td>
						<td><button type="button" class="button plrp-remove-ingredient"><?php esc_html_e( 'Remove', 'plrp-economy' ); ?></button></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<p><button type="button" class="button" id="plrp-add-ingredient"><?php esc_html_e( '+ Add Ingredient', 'plrp-economy' ); ?></button></p>

		<h2><?php esc_html_e( 'Crafting Tracker', 'plrp-economy' ); ?></h2>
		<?php if ( ! $tracker_available ) : ?>
			<p class="description"><?php esc_html_e( 'The Pulang Lupa Crafting Tracker plugin was not found (or its data file isn\'t writable), so this item won\'t be synced anywhere - the fields below are inert.', 'plrp-economy' ); ?></p>
		<?php elseif ( $tracker_matched ) : ?>
			<p class="description"><span class="dashicons dashicons-yes" style="color:#46b450;"></span> <?php esc_html_e( 'Already in the Crafting Tracker (matched by item name) - its ingredient list is kept in sync automatically whenever you save. Its category and reward id stay as set in the tracker; the fields below are only used for items not yet in the tracker.', 'plrp-economy' ); ?></p>
		<?php else : ?>
			<p class="description"><?php esc_html_e( 'Not in the Crafting Tracker yet. Fill in both fields below to add it as a new recipe the next time this saves - leave either blank to skip adding it (nothing is guessed automatically, since a wrong in-game id would break crafting for players).', 'plrp-economy' ); ?></p>
		<?php endif; ?>

		<table class="form-table">
			<tr>
				<th><label for="plrp-tracker-category"><?php esc_html_e( 'Tracker Category', 'plrp-economy' ); ?></label></th>
				<td>
					<input type="text" id="plrp-tracker-category" name="tracker_category" list="plrp-tracker-categories" value="<?php echo $is_edit ? esc_attr( $item->tracker_category ) : ''; ?>" placeholder="e.g. panday1" <?php disabled( ! $tracker_available ); ?> />
					<datalist id="plrp-tracker-categories">
						<?php foreach ( $tracker_categories as $cat ) : ?>
							<option value="<?php echo esc_attr( $cat ); ?>"></option>
						<?php endforeach; ?>
					</datalist>
					<p class="description"><?php esc_html_e( 'Must match an existing job/category prefix used by the tracker (e.g. matadero, panday1, armero3) so job-gated visibility works correctly.', 'plrp-economy' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="plrp-tracker-game-id"><?php esc_html_e( 'Game Item ID (reward)', 'plrp-economy' ); ?></label></th>
				<td>
					<input type="text" id="plrp-tracker-game-id" name="tracker_game_item_id" value="<?php echo $is_edit ? esc_attr( $item->game_item_id ) : ''; ?>" placeholder="the in-game item id this recipe produces" <?php disabled( ! $tracker_available ); ?> />
				</td>
			</tr>
		</table>

		<?php if ( $is_edit && $breakdown ) : ?>
			<div class="postbox" style="max-width:420px;padding:12px;">
				<h2 class="hndle"><span><?php esc_html_e( 'Current Calculation', 'plrp-economy' ); ?></span></h2>
				<div class="inside">
					<p><?php esc_html_e( 'Total Material Cost:', 'plrp-economy' ); ?> <strong><?php echo esc_html( number_format( $breakdown['total_mat_cost'], 2 ) ); ?></strong></p>
					<p><?php esc_html_e( 'Markup:', 'plrp-economy' ); ?> <strong><?php echo esc_html( number_format( $breakdown['markup'], 2 ) ); ?></strong></p>
					<p><?php esc_html_e( 'Internal Selling Price:', 'plrp-economy' ); ?> <strong><?php echo esc_html( number_format( $breakdown['selling_price'], 2 ) ); ?></strong></p>
					<?php if ( $breakdown['srp_enabled'] ) : ?>
						<p><?php esc_html_e( 'Published SRP:', 'plrp-economy' ); ?> <strong><?php echo esc_html( number_format( $breakdown['srp'], 2 ) ); ?></strong></p>
					<?php endif; ?>
					<p class="description"><?php esc_html_e( 'Save the form to refresh this after changing ingredients.', 'plrp-economy' ); ?></p>
				</div>
			</div>
		<?php endif; ?>

		<p class="submit">
			<button class="button button-primary"><?php esc_html_e( 'Save Item', 'plrp-economy' ); ?></button>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=plrp-economy-items' ) ); ?>" class="button"><?php esc_html_e( 'Cancel', 'plrp-economy' ); ?></a>
		</p>
	</form>
</div>
