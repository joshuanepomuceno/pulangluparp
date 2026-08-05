<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var array $professions */
?>
<div class="wrap plrp-admin">
	<h1><?php esc_html_e( 'Professions', 'plrp-economy' ); ?></h1>
	<p class="description"><?php esc_html_e( 'Each profession is one crafting "job" (Pagkain, Panday, Armero, ...). The markup % is applied to every item\'s total material cost to get its internal selling price. Turning on "Publish as SRP" applies the extra buffer/margin/rounding step used for the public price list, mirroring the original SRP sheet.', 'plrp-economy' ); ?></p>

	<table class="widefat striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Name', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Markup %', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Publish as SRP?', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Buffer %', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Margin %', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Round up to', 'plrp-economy' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $professions as $p ) : ?>
			<tr>
				<form method="post" class="plrp-inline-form">
					<?php wp_nonce_field( 'plrp_save_profession' ); ?>
					<input type="hidden" name="plrp_action" value="save_profession" />
					<input type="hidden" name="profession_id" value="<?php echo esc_attr( $p->id ); ?>" />
					<td><input type="text" name="name" value="<?php echo esc_attr( $p->name ); ?>" required /></td>
					<td><input type="number" step="0.0001" min="0" name="markup_pct" value="<?php echo esc_attr( $p->markup_pct ); ?>" /> <span class="description"><?php echo esc_html( round( $p->markup_pct * 100, 2 ) ); ?>%</span></td>
					<td><input type="checkbox" name="srp_enabled" value="1" <?php checked( $p->srp_enabled, 1 ); ?> /></td>
					<td><input type="number" step="0.0001" min="0" name="srp_buffer_pct" value="<?php echo esc_attr( $p->srp_buffer_pct ); ?>" /></td>
					<td><input type="number" step="0.0001" min="0" name="srp_margin_pct" value="<?php echo esc_attr( $p->srp_margin_pct ); ?>" /></td>
					<td><input type="number" step="0.0001" min="0" name="srp_rounding_increment" value="<?php echo esc_attr( $p->srp_rounding_increment ); ?>" /></td>
					<td>
						<button class="button button-small"><?php esc_html_e( 'Save', 'plrp-economy' ); ?></button>
				</form>
				<form method="post" class="plrp-inline-form" onsubmit="return confirm('<?php echo esc_js( __( 'Delete this profession? Items must be moved/removed first.', 'plrp-economy' ) ); ?>');">
					<?php wp_nonce_field( 'plrp_delete_profession' ); ?>
					<input type="hidden" name="plrp_action" value="delete_profession" />
					<input type="hidden" name="profession_id" value="<?php echo esc_attr( $p->id ); ?>" />
						<button class="button button-small button-link-delete"><?php esc_html_e( 'Delete', 'plrp-economy' ); ?></button>
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<div class="postbox" style="margin-top:20px;max-width:500px;">
		<h2 class="hndle"><span><?php esc_html_e( 'Add Profession', 'plrp-economy' ); ?></span></h2>
		<div class="inside">
			<form method="post">
				<?php wp_nonce_field( 'plrp_save_profession' ); ?>
				<input type="hidden" name="plrp_action" value="save_profession" />
				<p>
					<label><?php esc_html_e( 'Name', 'plrp-economy' ); ?></label>
					<input type="text" name="name" required />
				</p>
				<p>
					<label><?php esc_html_e( 'Markup % (e.g. 0.3 = 30%)', 'plrp-economy' ); ?></label>
					<input type="number" step="0.0001" min="0" name="markup_pct" value="0" />
				</p>
				<p>
					<label><input type="checkbox" name="srp_enabled" value="1" /> <?php esc_html_e( 'Publish as SRP (buffer + margin + round up)', 'plrp-economy' ); ?></label>
				</p>
				<p>
					<label><?php esc_html_e( 'Buffer % (default 0.15)', 'plrp-economy' ); ?></label>
					<input type="number" step="0.0001" min="0" name="srp_buffer_pct" value="0.15" />
				</p>
				<p>
					<label><?php esc_html_e( 'Margin % (default 0.25)', 'plrp-economy' ); ?></label>
					<input type="number" step="0.0001" min="0" name="srp_margin_pct" value="0.25" />
				</p>
				<p>
					<label><?php esc_html_e( 'Round up to nearest (default 0.1)', 'plrp-economy' ); ?></label>
					<input type="number" step="0.0001" min="0" name="srp_rounding_increment" value="0.1" />
				</p>
				<button class="button button-primary"><?php esc_html_e( 'Add Profession', 'plrp-economy' ); ?></button>
			</form>
		</div>
	</div>
</div>
