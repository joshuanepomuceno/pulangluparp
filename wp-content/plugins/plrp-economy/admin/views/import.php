<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var array|false $report */
?>
<div class="wrap plrp-admin">
	<h1><?php esc_html_e( 'Import Spreadsheet', 'plrp-economy' ); ?></h1>
	<p class="description">
		<?php esc_html_e( 'One-time seed: upload the working sheet (.xlsx) to populate Materials, Professions, and Items. Re-running this will update existing materials/professions by name and add items again, so use it for the initial import - after that, edit prices and recipes directly in the screens above.', 'plrp-economy' ); ?>
	</p>

	<form method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'plrp_import_spreadsheet' ); ?>
		<input type="hidden" name="plrp_action" value="import_spreadsheet" />
		<input type="file" name="plrp_import_file" accept=".xlsx" required />
		<button class="button button-primary"><?php esc_html_e( 'Import', 'plrp-economy' ); ?></button>
	</form>

	<?php if ( $report ) : ?>
		<h2><?php esc_html_e( 'Last Import Report', 'plrp-economy' ); ?></h2>
		<p><?php echo esc_html( sprintf( __( '%d materials imported.', 'plrp-economy' ), $report['materials_imported'] ) ); ?></p>

		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Profession', 'plrp-economy' ); ?></th>
					<th><?php esc_html_e( 'Items Imported', 'plrp-economy' ); ?></th>
					<th><?php esc_html_e( 'Warnings', 'plrp-economy' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $report['professions'] as $name => $result ) : ?>
				<tr>
					<td><?php echo esc_html( $name ); ?></td>
					<td><?php echo esc_html( $result['items'] ); ?></td>
					<td>
						<?php if ( ! empty( $result['warnings'] ) ) : ?>
							<details>
								<summary><?php echo esc_html( count( $result['warnings'] ) ); ?> <?php esc_html_e( 'warning(s)', 'plrp-economy' ); ?></summary>
								<ul>
									<?php foreach ( $result['warnings'] as $w ) : ?>
										<li><?php echo esc_html( $w ); ?></li>
									<?php endforeach; ?>
								</ul>
							</details>
						<?php else : ?>
							&mdash;
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ( ! empty( $report['warnings'] ) ) : ?>
			<h3><?php esc_html_e( 'General Warnings', 'plrp-economy' ); ?></h3>
			<ul>
				<?php foreach ( $report['warnings'] as $w ) : ?>
					<li><?php echo esc_html( $w ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php endif; ?>
</div>
