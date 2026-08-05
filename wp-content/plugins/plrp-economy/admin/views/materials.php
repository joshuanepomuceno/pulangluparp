<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var array $materials */
/** @var string $search */
?>
<div class="wrap plrp-admin">
	<h1><?php esc_html_e( 'Base Materials', 'plrp-economy' ); ?></h1>
	<p class="description"><?php esc_html_e( 'This is the master price list ("ECO-BASE PRICES") every recipe pulls its ingredient costs from. Changing a price here recalculates every item that uses it.', 'plrp-economy' ); ?></p>
	<p class="description"><?php esc_html_e( 'Game Item ID is the in-game item id the Crafting Tracker uses for this material - it\'s filled in automatically whenever it\'s already known from the tracker\'s recipes, so most rows should never need manual editing.', 'plrp-economy' ); ?></p>

	<div class="plrp-columns">
		<div class="plrp-col-main">
			<form method="get" class="plrp-search-form">
				<input type="hidden" name="page" value="plrp-economy-materials" />
				<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search materials…', 'plrp-economy' ); ?>" />
				<button class="button"><?php esc_html_e( 'Search', 'plrp-economy' ); ?></button>
			</form>

			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Name', 'plrp-economy' ); ?></th>
						<th><?php esc_html_e( 'Base Price', 'plrp-economy' ); ?></th>
						<th><?php esc_html_e( 'Unit', 'plrp-economy' ); ?></th>
						<th><?php esc_html_e( 'Game Item ID', 'plrp-economy' ); ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php if ( empty( $materials ) ) : ?>
					<tr><td colspan="5"><?php esc_html_e( 'No materials yet. Add one on the right, or use Import Spreadsheet.', 'plrp-economy' ); ?></td></tr>
				<?php else : ?>
					<?php foreach ( $materials as $m ) : ?>
						<tr>
							<td>
								<form method="post" class="plrp-inline-form">
									<?php wp_nonce_field( 'plrp_save_material' ); ?>
									<input type="hidden" name="plrp_action" value="save_material" />
									<input type="text" name="name" value="<?php echo esc_attr( $m->name ); ?>" required />
							</td>
							<td>
									<input type="number" step="0.0001" min="0" name="base_price" value="<?php echo esc_attr( $m->base_price ); ?>" required />
							</td>
							<td>
									<input type="text" name="unit" value="<?php echo esc_attr( $m->unit ); ?>" placeholder="pc" />
							</td>
							<td>
									<input type="text" name="game_item_id" value="<?php echo esc_attr( $m->game_item_id ); ?>" placeholder="<?php esc_attr_e( 'auto-filled from tracker', 'plrp-economy' ); ?>" />
							</td>
							<td>
									<button class="button button-small"><?php esc_html_e( 'Save', 'plrp-economy' ); ?></button>
								</form>
								<form method="post" class="plrp-inline-form" onsubmit="return confirm('<?php echo esc_js( __( 'Delete this material?', 'plrp-economy' ) ); ?>');">
									<?php wp_nonce_field( 'plrp_delete_material' ); ?>
									<input type="hidden" name="plrp_action" value="delete_material" />
									<input type="hidden" name="material_id" value="<?php echo esc_attr( $m->id ); ?>" />
									<button class="button button-small button-link-delete"><?php esc_html_e( 'Delete', 'plrp-economy' ); ?></button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

		<div class="plrp-col-side">
			<div class="postbox">
				<h2 class="hndle"><span><?php esc_html_e( 'Add Material', 'plrp-economy' ); ?></span></h2>
				<div class="inside">
					<form method="post">
						<?php wp_nonce_field( 'plrp_save_material' ); ?>
						<input type="hidden" name="plrp_action" value="save_material" />
						<p>
							<label><?php esc_html_e( 'Name', 'plrp-economy' ); ?></label>
							<input type="text" name="name" required />
						</p>
						<p>
							<label><?php esc_html_e( 'Base Price', 'plrp-economy' ); ?></label>
							<input type="number" step="0.0001" min="0" name="base_price" required />
						</p>
						<p>
							<label><?php esc_html_e( 'Unit (optional)', 'plrp-economy' ); ?></label>
							<input type="text" name="unit" placeholder="pc" />
						</p>
						<p>
							<label><?php esc_html_e( 'Game Item ID (optional)', 'plrp-economy' ); ?></label>
							<input type="text" name="game_item_id" placeholder="<?php esc_attr_e( 'auto-filled from tracker if known', 'plrp-economy' ); ?>" />
						</p>
						<button class="button button-primary"><?php esc_html_e( 'Add Material', 'plrp-economy' ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
