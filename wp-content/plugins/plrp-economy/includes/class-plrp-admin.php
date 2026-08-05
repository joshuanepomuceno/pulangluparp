<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP-admin screens: Materials, Professions, Items, Import.
 * Everything requires the manage_options capability.
 */
class PLSRP_Admin {

	const CAP = 'manage_options';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'handle_actions' ) );
		add_action( 'admin_notices', array( $this, 'render_notices' ) );
	}

	public function register_menu() {
		add_menu_page(
			__( 'PLRP Economy', 'plrp-economy' ),
			__( 'PLRP Economy', 'plrp-economy' ),
			self::CAP,
			'plrp-economy-materials',
			array( $this, 'render_materials_page' ),
			'dashicons-money-alt',
			56
		);

		add_submenu_page( 'plrp-economy-materials', __( 'Materials', 'plrp-economy' ), __( 'Materials', 'plrp-economy' ), self::CAP, 'plrp-economy-materials', array( $this, 'render_materials_page' ) );
		add_submenu_page( 'plrp-economy-materials', __( 'Professions', 'plrp-economy' ), __( 'Professions', 'plrp-economy' ), self::CAP, 'plrp-economy-professions', array( $this, 'render_professions_page' ) );
		add_submenu_page( 'plrp-economy-materials', __( 'Items', 'plrp-economy' ), __( 'Items', 'plrp-economy' ), self::CAP, 'plrp-economy-items', array( $this, 'render_items_page' ) );
		add_submenu_page( 'plrp-economy-materials', __( 'Import Spreadsheet', 'plrp-economy' ), __( 'Import Spreadsheet', 'plrp-economy' ), self::CAP, 'plrp-economy-import', array( $this, 'render_import_page' ) );
		add_submenu_page( 'plrp-economy-materials', __( 'Crafting Tracker Sync', 'plrp-economy' ), __( 'Crafting Tracker Sync', 'plrp-economy' ), self::CAP, 'plrp-economy-tracker-sync', array( $this, 'render_tracker_sync_page' ) );

		// Registered with a null parent so it doesn't get a sidebar nav entry
		// (it's only ever reached via "Add New" / "Edit" links from the Items
		// list) but WordPress still recognizes admin.php?page=plrp-economy-item-edit
		// as a valid, permission-checked page. Using add_submenu_page() with a
		// real parent and then remove_submenu_page() looks equivalent but isn't:
		// remove_submenu_page() also strips the page from WordPress's internal
		// access-check list, so even admins get a "Sorry, you are not allowed
		// to access this page" error - that's the bug this avoids.
		add_submenu_page( null, __( 'Item', 'plrp-economy' ), __( 'Item', 'plrp-economy' ), self::CAP, 'plrp-economy-item-edit', array( $this, 'render_item_edit_page' ) );
	}

	private function redirect_with_notice( $page, $notice, $type = 'success', $extra_args = array() ) {
		$args = array_merge( array( 'page' => $page, 'plrp_notice' => $notice, 'plrp_notice_type' => $type ), $extra_args );
		wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
		exit;
	}

	public function render_notices() {
		if ( empty( $_GET['plrp_notice'] ) ) {
			return;
		}
		$type = isset( $_GET['plrp_notice_type'] ) && 'error' === $_GET['plrp_notice_type'] ? 'error' : 'success';
		printf(
			'<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
			esc_attr( $type ),
			esc_html( sanitize_text_field( wp_unslash( $_GET['plrp_notice'] ) ) )
		);
	}

	/**
	 * Central POST handler for all four screens.
	 */
	public function handle_actions() {
		if ( ! current_user_can( self::CAP ) || empty( $_POST['plrp_action'] ) ) {
			return;
		}

		$action = sanitize_key( $_POST['plrp_action'] );

		switch ( $action ) {
			case 'save_material':
				check_admin_referer( 'plrp_save_material' );
				$name         = sanitize_text_field( wp_unslash( $_POST['name'] ) );
				$price        = (float) $_POST['base_price'];
				$unit         = isset( $_POST['unit'] ) ? sanitize_text_field( wp_unslash( $_POST['unit'] ) ) : '';
				$game_item_id = isset( $_POST['game_item_id'] ) ? sanitize_text_field( wp_unslash( $_POST['game_item_id'] ) ) : '';
				PLSRP_Materials::upsert( $name, $price, $unit, $game_item_id );
				$this->run_tracker_sync();
				$this->redirect_with_notice( 'plrp-economy-materials', __( 'Material saved.', 'plrp-economy' ) );
				break;

			case 'delete_material':
				check_admin_referer( 'plrp_delete_material' );
				$result = PLSRP_Materials::delete( (int) $_POST['material_id'] );
				if ( is_wp_error( $result ) ) {
					$this->redirect_with_notice( 'plrp-economy-materials', $result->get_error_message(), 'error' );
				}
				$this->redirect_with_notice( 'plrp-economy-materials', __( 'Material deleted.', 'plrp-economy' ) );
				break;

			case 'save_profession':
				check_admin_referer( 'plrp_save_profession' );
				PLSRP_Professions::save( ! empty( $_POST['profession_id'] ) ? (int) $_POST['profession_id'] : 0, $_POST );
				$this->redirect_with_notice( 'plrp-economy-professions', __( 'Profession saved.', 'plrp-economy' ) );
				break;

			case 'delete_profession':
				check_admin_referer( 'plrp_delete_profession' );
				$result = PLSRP_Professions::delete( (int) $_POST['profession_id'] );
				if ( is_wp_error( $result ) ) {
					$this->redirect_with_notice( 'plrp-economy-professions', $result->get_error_message(), 'error' );
				}
				$this->redirect_with_notice( 'plrp-economy-professions', __( 'Profession deleted.', 'plrp-economy' ) );
				break;

			case 'save_item':
				check_admin_referer( 'plrp_save_item' );
				$item_id          = ! empty( $_POST['item_id'] ) ? (int) $_POST['item_id'] : null;
				$profession_id    = (int) $_POST['profession_id'];
				$name             = sanitize_text_field( wp_unslash( $_POST['name'] ) );
				$notes            = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';
				$tracker_category = isset( $_POST['tracker_category'] ) ? sanitize_text_field( wp_unslash( $_POST['tracker_category'] ) ) : '';
				$tracker_game_id  = isset( $_POST['tracker_game_item_id'] ) ? sanitize_text_field( wp_unslash( $_POST['tracker_game_item_id'] ) ) : '';

				$ingredients = array();
				if ( ! empty( $_POST['ingredient_material'] ) && is_array( $_POST['ingredient_material'] ) ) {
					foreach ( $_POST['ingredient_material'] as $i => $material_id ) {
						$qty = isset( $_POST['ingredient_quantity'][ $i ] ) ? (float) $_POST['ingredient_quantity'][ $i ] : 0;
						if ( (int) $material_id > 0 && $qty > 0 ) {
							$ingredients[] = array( 'material_id' => (int) $material_id, 'quantity' => $qty );
						}
					}
				}

				$saved_id = PLSRP_Items::save( $item_id, $profession_id, $name, $notes, $ingredients, $tracker_category, $tracker_game_id );
				$this->run_tracker_sync();
				$this->redirect_with_notice( 'plrp-economy-items', __( 'Item saved.', 'plrp-economy' ) );
				break;

			case 'delete_item':
				check_admin_referer( 'plrp_delete_item' );
				PLSRP_Items::delete( (int) $_POST['item_id'] );
				$this->run_tracker_sync();
				$this->redirect_with_notice( 'plrp-economy-items', __( 'Item deleted.', 'plrp-economy' ) );
				break;

			case 'import_spreadsheet':
				check_admin_referer( 'plrp_import_spreadsheet' );
				$this->handle_import_upload();
				break;

			case 'tracker_sync_now':
				check_admin_referer( 'plrp_tracker_sync' );
				$this->run_tracker_sync();
				$this->redirect_with_notice( 'plrp-economy-tracker-sync', __( 'Sync complete. See the report below.', 'plrp-economy' ) );
				break;
		}
	}

	/**
	 * Runs the Crafting Tracker reconciliation and stashes the report for the
	 * Tracker Sync screen to display. Called after every material/item save,
	 * delete, and spreadsheet import so recipes.json never drifts far behind
	 * without needing a separate manual step - but it's also exposed as its
	 * own "Sync Now" button for peace of mind / re-running after fixing a
	 * skipped item.
	 */
	private function run_tracker_sync() {
		$report = PLSRP_Tracker_Sync::sync_all();
		set_transient( 'plsrp_last_tracker_sync_report', $report, HOUR_IN_SECONDS );
		return $report;
	}

	private function handle_import_upload() {
		if ( empty( $_FILES['plrp_import_file']['tmp_name'] ) || UPLOAD_ERR_OK !== $_FILES['plrp_import_file']['error'] ) {
			$this->redirect_with_notice( 'plrp-economy-import', __( 'No file uploaded, or the upload failed.', 'plrp-economy' ), 'error' );
			return;
		}

		$file = $_FILES['plrp_import_file'];
		$ext  = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		if ( 'xlsx' !== $ext ) {
			$this->redirect_with_notice( 'plrp-economy-import', __( 'Please upload an .xlsx file.', 'plrp-economy' ), 'error' );
			return;
		}

		try {
			$report = PLSRP_Importer::import_from_file( $file['tmp_name'] );
		} catch ( Exception $e ) {
			$this->redirect_with_notice( 'plrp-economy-import', 'Import failed: ' . $e->getMessage(), 'error' );
			return;
		}

		set_transient( 'plsrp_last_import_report', $report, HOUR_IN_SECONDS );
		$this->run_tracker_sync();

		$total_items = array_sum( wp_list_pluck( $report['professions'], 'items' ) );
		$message     = sprintf(
			/* translators: 1: materials imported, 2: items imported */
			__( 'Import complete: %1$d materials, %2$d items. See the report below (and the Crafting Tracker Sync page for how the tracker was reconciled).', 'plrp-economy' ),
			$report['materials_imported'],
			$total_items
		);

		$this->redirect_with_notice( 'plrp-economy-import', $message );
	}

	// ---------------------------------------------------------------------
	// Render callbacks - each includes a view file that does the HTML.
	// ---------------------------------------------------------------------

	public function render_materials_page() {
		$search    = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
		$materials = PLSRP_Materials::all( $search );
		include PLSRP_DIR . 'admin/views/materials.php';
	}

	public function render_professions_page() {
		$professions = PLSRP_Professions::all();
		include PLSRP_DIR . 'admin/views/professions.php';
	}

	public function render_items_page() {
		$professions   = PLSRP_Professions::all();
		$filter        = isset( $_GET['profession_id'] ) ? (int) $_GET['profession_id'] : 0;
		$items         = array();
		$professions_by_id = array();
		foreach ( $professions as $p ) {
			$professions_by_id[ $p->id ] = $p;
		}

		if ( $filter ) {
			$items = PLSRP_Items::all_for_profession( $filter );
		} else {
			$items = PLSRP_Items::all();
		}

		include PLSRP_DIR . 'admin/views/items.php';
	}

	public function render_item_edit_page() {
		$item_id           = isset( $_GET['item_id'] ) ? (int) $_GET['item_id'] : 0;
		$item              = $item_id ? PLSRP_Items::get( $item_id ) : null;
		$ingredients       = $item_id ? PLSRP_Items::get_ingredients( $item_id ) : array();
		$professions       = PLSRP_Professions::all();
		$materials         = PLSRP_Materials::all();
		$breakdown         = $item_id ? PLSRP_Calculator::breakdown_for_item_id( $item_id ) : null;
		$tracker_available  = PLSRP_Tracker_Sync::is_available();
		$tracker_categories = $tracker_available ? PLSRP_Tracker_Sync::known_categories() : array();
		$tracker_matched    = $item && $tracker_available && PLSRP_Tracker_Sync::is_recipe_present( $item->name );

		include PLSRP_DIR . 'admin/views/item-edit.php';
	}

	public function render_import_page() {
		$report = get_transient( 'plsrp_last_import_report' );
		include PLSRP_DIR . 'admin/views/import.php';
	}

	public function render_tracker_sync_page() {
		$available = PLSRP_Tracker_Sync::is_available();
		$report    = get_transient( 'plsrp_last_tracker_sync_report' );
		$categories = $available ? PLSRP_Tracker_Sync::known_categories() : array();
		include PLSRP_DIR . 'admin/views/tracker-sync.php';
	}
}
