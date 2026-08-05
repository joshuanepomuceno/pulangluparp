<?php
// Fired when the plugin is deleted from wp-admin > Plugins.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// We deliberately do NOT drop the plrp_materials / plrp_professions /
// plrp_items / plrp_item_ingredients tables here - this is live economy
// data for the RP server, and losing it on an accidental deactivate+delete
// would be far worse than leaving a few unused tables behind. Remove them
// manually via phpMyAdmin/wp-cli if you really want a clean uninstall:
//
//   DROP TABLE wp_plrp_materials, wp_plrp_professions, wp_plrp_items, wp_plrp_item_ingredients;

delete_option( 'plsrp_db_version' );
delete_transient( 'plsrp_last_import_report' );
