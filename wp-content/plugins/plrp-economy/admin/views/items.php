<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var array $professions */
/** @var array $items */
/** @var int $filter */
?>
<div class="wrap plrp-admin">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Items', 'plrp-economy' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=plrp-economy-item-edit' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'plrp-economy' ); ?></a>
	<hr class="wp-header-end" />

	<ul class="subsubsub">
		<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=plrp-economy-items' ) ); ?>" <?php echo $filter ? '' : 'class="current"'; ?>><?php esc_html_e( 'All professions', 'plrp-economy' ); ?></a></li>
		<?php foreach ( $professions as $p ) : ?>
			| <li><a href="<?php echo esc_url( admin_url( 'admin.php?page=plrp-economy-items&profession_id=' . $p->id ) ); ?>" <?php echo ( $filter === (int) $p->id ) ? 'class="current"' : ''; ?>><?php echo esc_html( $p->name ); ?></a></li>
		<?php endforeach; ?>
	</ul>

	<table class="widefat striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Item', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Profession', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Total Mat. Cost', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Selling Price', 'plrp-economy' ); ?></th>
				<th><?php esc_html_e( 'Display Price', 'plrp-economy' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php if ( empty( $items ) ) : ?>
			<tr><td colspan="6"><?php esc_html_e( 'No items yet.', 'plrp-economy' ); ?></td></tr>
		<?php else : ?>
			<?php foreach ( $items as $item ) : ?>
				<?php $breakdown = PLSRP_Calculator::breakdown_for_item_id( $item->id ); ?>
				<tr>
					<td>
						<strong>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=plrp-economy-item-edit&item_id=' . $item->id ) ); ?>">
								<?php echo esc_html( $item->name ); ?>
							</a>
						</strong>
					</td>
					<td><?php echo esc_html( isset( $item->profession_name ) ? $item->profession_name : '' ); ?></td>
					<td><?php echo esc_html( number_format( $breakdown['total_mat_cost'], 2 ) ); ?></td>
					<td><?php echo esc_html( number_format( $breakdown['selling_price'], 2 ) ); ?></td>
					<td><strong><?php echo esc_html( number_format( $breakdown['display_price'], 2 ) ); ?></strong><?php echo $breakdown['srp_enabled'] ? ' <span class="description">(SRP)</span>' : ''; ?></td>
					<td>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=plrp-economy-item-edit&item_id=' . $item->id ) ); ?>" class="button button-small"><?php esc_html_e( 'Edit', 'plrp-economy' ); ?></a>
						<form method="post" class="plrp-inline-form" onsubmit="return confirm('<?php echo esc_js( __( 'Delete this item?', 'plrp-economy' ) ); ?>');">
							<?php wp_nonce_field( 'plrp_delete_item' ); ?>
							<input type="hidden" name="plrp_action" value="delete_item" />
							<input type="hidden" name="item_id" value="<?php echo esc_attr( $item->id ); ?>" />
							<button class="button button-small button-link-delete"><?php esc_html_e( 'Delete', 'plrp-economy' ); ?></button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
</div>
