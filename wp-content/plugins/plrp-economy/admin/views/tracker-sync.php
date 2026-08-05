<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var bool $available */
/** @var array|false $report */
/** @var array $categories */
?>
<div class="wrap plrp-admin">
	<h1><?php esc_html_e( 'Crafting Tracker Sync', 'plrp-economy' ); ?></h1>
	<p class="description">
		<?php esc_html_e( 'Keeps the Pulang Lupa Crafting Tracker\'s recipes.json in sync with the items and ingredients maintained here. This runs automatically after every material/item save, delete, and spreadsheet import - use "Sync Now" to re-run it by hand (e.g. after fixing a skipped item).', 'plrp-economy' ); ?>
	</p>
	<p class="description">
		<strong><?php esc_html_e( 'How this works:', 'plrp-economy' ); ?></strong>
		<?php esc_html_e( 'Items already in the tracker (matched by name) get their ingredient list refreshed here - their category and in-game reward id are left exactly as the tracker has them. Items not yet in the tracker are only ever added if you\'ve explicitly set a Tracker Category and Game Item ID on that item (Items screen); nothing is ever guessed. Nothing is ever removed from the tracker.', 'plrp-economy' ); ?>
	</p>

	<?php if ( ! $available ) : ?>
		<div class="notice notice-warning inline">
			<p><?php esc_html_e( 'The Pulang Lupa Crafting Tracker plugin wasn\'t found, or its data/recipes.json file isn\'t writable by this site. Everything else in PLRP Economy still works - the tracker is just skipped.', 'plrp-economy' ); ?></p>
		</div>
	<?php endif; ?>

	<form method="post">
		<?php wp_nonce_field( 'plrp_tracker_sync' ); ?>
		<input type="hidden" name="plrp_action" value="tracker_sync_now" />
		<button class="button button-primary" <?php disabled( ! $available ); ?>><?php esc_html_e( 'Sync Now', 'plrp-economy' ); ?></button>
	</form>

	<?php if ( $report ) : ?>
		<h2><?php esc_html_e( 'Last Sync Report', 'plrp-economy' ); ?></h2>

		<?php if ( ! $report['available'] ) : ?>
			<p><?php esc_html_e( 'The tracker was not available at the time of the last sync.', 'plrp-economy' ); ?></p>
		<?php else : ?>
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: updated count, 2: added count, 3: skipped count, 4: backfilled materials count */
						__( '%1$d recipe(s) updated, %2$d added, %3$d skipped. %4$d material(s) had their Game Item ID auto-filled from the tracker.', 'plrp-economy' ),
						count( $report['updated'] ),
						count( $report['added'] ),
						count( $report['skipped'] ),
						$report['backfilled_materials']
					)
				);
				?>
			</p>

			<?php if ( ! empty( $report['updated'] ) ) : ?>
				<details>
					<summary><?php echo esc_html( count( $report['updated'] ) ); ?> <?php esc_html_e( 'updated', 'plrp-economy' ); ?></summary>
					<ul><?php foreach ( $report['updated'] as $name ) : ?><li><?php echo esc_html( $name ); ?></li><?php endforeach; ?></ul>
				</details>
			<?php endif; ?>

			<?php if ( ! empty( $report['added'] ) ) : ?>
				<details open>
					<summary><?php echo esc_html( count( $report['added'] ) ); ?> <?php esc_html_e( 'added as new recipes', 'plrp-economy' ); ?></summary>
					<ul><?php foreach ( $report['added'] as $name ) : ?><li><?php echo esc_html( $name ); ?></li><?php endforeach; ?></ul>
				</details>
			<?php endif; ?>

			<?php if ( ! empty( $report['skipped'] ) ) : ?>
				<details open>
					<summary><?php echo esc_html( count( $report['skipped'] ) ); ?> <?php esc_html_e( 'skipped', 'plrp-economy' ); ?></summary>
					<table class="widefat striped">
						<thead><tr><th><?php esc_html_e( 'Item', 'plrp-economy' ); ?></th><th><?php esc_html_e( 'Reason', 'plrp-economy' ); ?></th></tr></thead>
						<tbody>
						<?php foreach ( $report['skipped'] as $s ) : ?>
							<tr><td><?php echo esc_html( $s['item'] ); ?></td><td><?php echo esc_html( $s['reason'] ); ?></td></tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</details>
			<?php endif; ?>
		<?php endif; ?>
	<?php else : ?>
		<p class="description"><?php esc_html_e( 'No sync has run yet.', 'plrp-economy' ); ?></p>
	<?php endif; ?>

	<?php if ( ! empty( $categories ) ) : ?>
		<h2><?php esc_html_e( 'Existing Tracker Categories', 'plrp-economy' ); ?></h2>
		<p class="description"><?php esc_html_e( 'For reference when setting a Tracker Category on a new item.', 'plrp-economy' ); ?></p>
		<p><?php echo esc_html( implode( ', ', $categories ) ); ?></p>
	<?php endif; ?>
</div>
