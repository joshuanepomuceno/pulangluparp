<?php
/**
 * Plugin Name: Pulang Lupa Crafting Tracker
 * Description: Interactive crafting tracker for Pulang Lupa RP. Browse recipes, track what you're crafting, check off ingredients, and see a combined shopping list. Use the [crafting_tracker] shortcode on any page. Job-specific recipes are only visible to logged-in members of that job (see the Pulang Lupa Job Roles plugin); shared recipes remain public.
 * Version: 1.1.0
 * Author: Pulang Lupa RP
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PLCT_VERSION', '1.1.0' );
define( 'PLCT_URL', plugin_dir_url( __FILE__ ) );
define( 'PLCT_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Recipe "category" values in data/recipes.json are prefixed per job, with a
 * trailing digit for sub-groups (e.g. "panday1", "armero3"). This maps those
 * prefixes to the job role slugs registered by the Pulang Lupa Job Roles
 * plugin. Anything not listed here (food, dessert, drinks, items, rekado, ...)
 * is treated as shared/public and shown to everyone.
 */
function plct_job_category_prefixes() {
    return [
        'matadero'  => 'matadero',
        'ensayador' => 'ensayador',
        'panday'    => 'panday',
        'doctor'    => 'doktor', // recipe data uses the English spelling; the role uses Tagalog
        'armero'    => 'armero',
        'katutubo'  => 'katutubo',
    ];
}
function plct_category_prefix( $category ) {
    return preg_replace( '/\d+$/', '', (string) $category );
}

function plct_register_assets() {
    wp_register_style( 'plct-style', PLCT_URL . 'assets/tracker.css', array(), PLCT_VERSION );
    wp_register_script( 'plct-script', PLCT_URL . 'assets/tracker.js', array(), PLCT_VERSION, true );

    $job      = function_exists( 'pljr_get_user_job' ) ? pljr_get_user_job() : null;
    $jobs     = function_exists( 'pljr_jobs' ) ? pljr_jobs() : [];
    $jobLabel = $job && isset( $jobs[ $job ] ) ? $jobs[ $job ] : null;

    wp_localize_script( 'plct-script', 'PLCT', array(
        'recipesUrl' => rest_url( 'plct/v1/recipes' ),
        'nonce'      => wp_create_nonce( 'wp_rest' ), // required for cookie-authenticated REST requests
        'loggedIn'   => is_user_logged_in(),
        'jobLabel'   => $jobLabel,
        'isAdmin'    => current_user_can( 'manage_options' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'plct_register_assets' );

function plct_shortcode() {
    wp_enqueue_style( 'plct-style' );
    wp_enqueue_script( 'plct-script' );
    return '<div id="plct-app" class="plct"><div class="plct-loading">Naglo-load ang mga recipe&hellip;</div></div>';
}
add_shortcode( 'crafting_tracker', 'plct_shortcode' );

/**
 * Authenticated, job-filtered recipes endpoint. Replaces direct access to
 * data/recipes.json (blocked via .htaccess) so job-specific recipes can't be
 * fetched by simply requesting the static file.
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'plct/v1', '/recipes', [
        'methods'             => 'GET',
        'permission_callback' => '__return_true', // public; filtering happens below
        'callback'            => 'plct_recipes_endpoint',
    ] );
} );

function plct_recipes_endpoint() {
    $path = PLCT_DIR . 'data/recipes.json';
    if ( ! file_exists( $path ) ) {
        return new WP_REST_Response( [], 200 );
    }
    $recipes = json_decode( (string) file_get_contents( $path ), true );
    if ( ! is_array( $recipes ) ) {
        return new WP_REST_Response( [], 200 );
    }

    if ( current_user_can( 'manage_options' ) ) {
        return new WP_REST_Response( $recipes, 200 ); // admins/staff see everything
    }

    $prefixes    = plct_job_category_prefixes();
    $userJob     = function_exists( 'pljr_get_user_job' ) ? pljr_get_user_job() : null;
    $jobPrefixes = array_flip( $prefixes ); // job slug => data prefix
    $allowedJobPrefix = $userJob && isset( $jobPrefixes[ $userJob ] ) ? $jobPrefixes[ $userJob ] : null;

    $filtered = array_values( array_filter( $recipes, function ( $r ) use ( $prefixes, $allowedJobPrefix ) {
        $prefix = plct_category_prefix( $r['category'] ?? '' );
        if ( ! isset( $prefixes[ $prefix ] ) ) {
            return true; // not a job-gated category — shared/public
        }
        return $allowedJobPrefix !== null && $prefix === $allowedJobPrefix;
    } ) );

    return new WP_REST_Response( $filtered, 200 );
}
