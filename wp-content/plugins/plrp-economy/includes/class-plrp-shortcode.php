<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public-facing [plrp_price_list] shortcode: a searchable, tabbed price list.
 *
 * Usage:
 *   [plrp_price_list]                    -> all professions, tabbed
 *   [plrp_price_list profession="panday"] -> just one profession's items
 *   [plrp_price_list show_ingredients="no"] -> hide the recipe breakdown column
 */
class PLSRP_Shortcode {

	public function __construct() {
		add_shortcode( 'plrp_price_list', array( $this, 'render' ) );
	}

	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'profession'       => '',
				'show_ingredients' => 'yes',
			),
			$atts,
			'plrp_price_list'
		);

		wp_enqueue_style( 'plrp-economy-public' );
		wp_enqueue_script( 'plrp-economy-public' );

		$all_professions = PLSRP_Professions::all();
		$show_ingredients = ( 'no' !== strtolower( $atts['show_ingredients'] ) );

		if ( ! empty( $atts['profession'] ) ) {
			$slug = sanitize_title( $atts['profession'] );
			$all_professions = array_values(
				array_filter(
					$all_professions,
					function ( $p ) use ( $slug ) {
						return $p->slug === $slug;
					}
				)
			);
		}

		if ( empty( $all_professions ) ) {
			return '<p class="plrp-empty">' . esc_html__( 'No price list data available yet.', 'plrp-economy' ) . '</p>';
		}

		ob_start();
		?>
		<div class="plrp-price-list">
			<div class="plrp-price-list-controls">
				<input type="search" class="plrp-search" placeholder="<?php esc_attr_e( 'Search items or ingredients…', 'plrp-economy' ); ?>" />
			</div>

			<?php if ( count( $all_professions ) > 1 ) : ?>
				<div class="plrp-tabs" role="tablist">
					<button type="button" class="plrp-tab is-active" data-target="all"><?php esc_html_e( 'All', 'plrp-economy' ); ?></button>
					<?php foreach ( $all_professions as $p ) : ?>
						<button type="button" class="plrp-tab" data-target="<?php echo esc_attr( $p->slug ); ?>"><?php echo esc_html( $p->name ); ?></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php foreach ( $all_professions as $p ) : ?>
				<?php $items = PLSRP_Items::all_for_profession( $p->id ); ?>
				<div class="plrp-profession-group" data-profession="<?php echo esc_attr( $p->slug ); ?>">
					<h3 class="plrp-profession-title"><?php echo esc_html( $p->name ); ?></h3>

					<?php if ( empty( $items ) ) : ?>
						<p class="plrp-empty"><?php esc_html_e( 'No items yet.', 'plrp-economy' ); ?></p>
					<?php else : ?>
						<table class="plrp-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Item', 'plrp-economy' ); ?></th>
									<?php if ( $show_ingredients ) : ?>
										<th><?php esc_html_e( 'Ingredients', 'plrp-economy' ); ?></th>
									<?php endif; ?>
									<th><?php esc_html_e( 'Price', 'plrp-economy' ); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ( $items as $item ) : ?>
								<?php
								$breakdown  = PLSRP_Calculator::breakdown_for_item_id( $item->id );
								$ing_names  = wp_list_pluck( $breakdown['ingredients'], 'material_name' );
								$search_key = strtolower( $item->name . ' ' . implode( ' ', $ing_names ) );
								?>
								<tr data-search="<?php echo esc_attr( $search_key ); ?>">
									<td><?php echo esc_html( $item->name ); ?></td>
									<?php if ( $show_ingredients ) : ?>
										<td>
											<ul class="plrp-ingredients">
												<?php foreach ( $breakdown['ingredients'] as $ing ) : ?>
													<li><?php echo esc_html( $ing['material_name'] ); ?> &times; <?php echo esc_html( rtrim( rtrim( number_format( $ing['quantity'], 2 ), '0' ), '.' ) ); ?></li>
												<?php endforeach; ?>
											</ul>
										</td>
									<?php endif; ?>
									<td class="plrp-price"><?php echo esc_html( number_format( $breakdown['display_price'], 2 ) ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
