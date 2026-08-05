<?php
/**
 * Plugin Name: Pulang Lupa Job Roles
 * Description: Head/member account hierarchy for RP jobs (Matadero, Ensayador, Panday, Doktor, Armero, Katutubo). Heads self-manage their job's roster from a front-end portal; members get login-only accounts with no wp-admin access. Includes duty time-in/time-out tracking (web portal or Discord bot) and a per-member payroll calculator. Also powers job-based recipe visibility in the Crafting Tracker via [job_portal].
 * Version: 1.2.0
 * Author: Pulang Lupa RP
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PLJR_VERSION', '1.4.0' );
define( 'PLJR_URL', plugin_dir_url( __FILE__ ) );
define( 'PLJR_ROLES_VERSION', '1' );
define( 'PLJR_SCHEMA_VERSION', '1' );

/* -------------------------------------------------------------------------
   Job registry — single source of truth for job slugs/labels.
   Member-level roles (matadero, ensayador, panday, doktor, armero, katutubo)
   already exist (registered by the theme). This plugin adds the paired
   "<job>_head" role for each and everything needed to self-manage rosters.
------------------------------------------------------------------------- */
function pljr_jobs() {
    return [
        'matadero'  => 'Matadero',
        'ensayador' => 'Ensayador',
        'panday'    => 'Panday',
        'doktor'    => 'Doktor',
        'armero'    => 'Armero',
        'katutubo'  => 'Katutubo',
    ];
}
function pljr_head_role( $job ) { return $job . '_head'; }

add_action( 'init', function () {
    if ( get_option( 'pljr_roles_version' ) === PLJR_ROLES_VERSION ) {
        return;
    }
    foreach ( pljr_jobs() as $job => $label ) {
        $slug = pljr_head_role( $job );
        remove_role( $slug ); // ensure label/caps update on version bump
        add_role( $slug, $label . ' Head', [
            'read'              => true,
            'manage_job_roster' => true,
        ] );
    }
    update_option( 'pljr_roles_version', PLJR_ROLES_VERSION );
} );

/* -------------------------------------------------------------------------
   Duty log table (time in / time out per user, plus an hourly rate stored
   in user meta so a Head can turn logged hours into a salary figure).
------------------------------------------------------------------------- */
function pljr_duty_table() {
    global $wpdb;
    return $wpdb->prefix . 'pljr_duty_logs';
}
add_action( 'init', function () {
    if ( get_option( 'pljr_schema_version' ) === PLJR_SCHEMA_VERSION ) {
        return;
    }
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table           = pljr_duty_table();
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        job VARCHAR(32) NOT NULL,
        time_in DATETIME NOT NULL,
        time_out DATETIME NULL,
        duration_minutes INT UNSIGNED NULL,
        PRIMARY KEY  (id),
        KEY user_id (user_id),
        KEY job (job),
        KEY time_in (time_in)
    ) {$charset_collate};";
    dbDelta( $sql );
    update_option( 'pljr_schema_version', PLJR_SCHEMA_VERSION );
} );

/* -------------------------------------------------------------------------
   Helpers
------------------------------------------------------------------------- */
function pljr_get_user_job( $user = null ) {
    $user = $user ?: wp_get_current_user();
    if ( ! $user || ! $user->exists() ) return null;
    foreach ( array_keys( pljr_jobs() ) as $job ) {
        if ( in_array( $job, (array) $user->roles, true ) || in_array( pljr_head_role( $job ), (array) $user->roles, true ) ) {
            return $job;
        }
    }
    return null;
}
function pljr_user_is_head( $user = null ) {
    $user = $user ?: wp_get_current_user();
    if ( ! $user || ! $user->exists() ) return false;
    foreach ( array_keys( pljr_jobs() ) as $job ) {
        if ( in_array( pljr_head_role( $job ), (array) $user->roles, true ) ) return true;
    }
    return false;
}

/* -------------------------------------------------------------------------
   Duty log helpers
------------------------------------------------------------------------- */
function pljr_get_open_session( $user_id ) {
    global $wpdb;
    $table = pljr_duty_table();
    return $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM {$table} WHERE user_id = %d AND time_out IS NULL ORDER BY id DESC LIMIT 1",
        $user_id
    ) );
}
function pljr_clock_in( $user_id, $job ) {
    if ( pljr_get_open_session( $user_id ) ) return false; // already on duty
    global $wpdb;
    $wpdb->insert( pljr_duty_table(), [
        'user_id' => $user_id,
        'job'     => $job,
        'time_in' => current_time( 'mysql' ),
    ], [ '%d', '%s', '%s' ] );
    return true;
}
function pljr_clock_out( $user_id ) {
    $open = pljr_get_open_session( $user_id );
    if ( ! $open ) return false; // not on duty
    global $wpdb;
    $now      = current_time( 'mysql' );
    $minutes  = max( 0, round( ( strtotime( $now ) - strtotime( $open->time_in ) ) / 60 ) );
    $wpdb->update( pljr_duty_table(),
        [ 'time_out' => $now, 'duration_minutes' => $minutes ],
        [ 'id' => $open->id ],
        [ '%s', '%d' ], [ '%d' ]
    );
    return true;
}
function pljr_format_duration( $minutes ) {
    $minutes = (int) $minutes;
    $h = intdiv( $minutes, 60 );
    $m = $minutes % 60;
    return $h . 'h ' . $m . 'm';
}
function pljr_user_rate( $user_id ) {
    $rate = get_user_meta( $user_id, 'pljr_hourly_rate', true );
    return ( $rate === '' || $rate === false ) ? null : (float) $rate;
}
function pljr_set_user_rate( $user_id, $rate ) {
    update_user_meta( $user_id, 'pljr_hourly_rate', number_format( (float) $rate, 2, '.', '' ) );
}
/**
 * Total minutes worked per user for a job within a date range (inclusive),
 * counting only completed (clocked-out) sessions.
 */
function pljr_duty_totals( $job, $from, $to ) {
    global $wpdb;
    $table = pljr_duty_table();
    $rows = $wpdb->get_results( $wpdb->prepare(
        "SELECT user_id, SUM(duration_minutes) AS total_minutes
         FROM {$table}
         WHERE job = %s AND time_out IS NOT NULL AND DATE(time_in) BETWEEN %s AND %s
         GROUP BY user_id",
        $job, $from, $to
    ) );
    $out = [];
    foreach ( $rows as $r ) $out[ (int) $r->user_id ] = (int) $r->total_minutes;
    return $out;
}
/** Individual sessions for one user within a date range, most recent first. */
function pljr_duty_sessions( $user_id, $from, $to, $limit = 20 ) {
    global $wpdb;
    $table = pljr_duty_table();
    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$table}
         WHERE user_id = %d AND DATE(time_in) BETWEEN %s AND %s
         ORDER BY time_in DESC LIMIT %d",
        $user_id, $from, $to, $limit
    ) );
}
/**
 * Every member's sessions for a whole job within a date range, most recent
 * first — one query for the team rather than one per member. This is what
 * backs the Duty tab's consolidated log, so a Head can see who clocked in
 * or out at any point in the day without opening each member individually.
 */
function pljr_duty_log_for_job( $job, $from, $to, $limit = 200 ) {
    global $wpdb;
    $table = pljr_duty_table();
    return $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$table}
         WHERE job = %s AND DATE(time_in) BETWEEN %s AND %s
         ORDER BY time_in DESC LIMIT %d",
        $job, $from, $to, $limit
    ) );
}
function pljr_default_range() {
    return [ date( 'Y-m-01' ), date( 'Y-m-d' ) ]; // start of this month -> today
}
function pljr_sanitize_date( $value, $fallback ) {
    if ( is_string( $value ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
        return $value;
    }
    return $fallback;
}

/**
 * Head-dashboard tabs. Each tab's data is only queried when it's the active
 * one, so the page stays cheap to render as the roster and duty logs grow.
 */
function pljr_dashboard_tabs() {
    return [
        'roster'  => [ 'label' => 'Mga Kasapi',         'icon' => '&#128101;' ],
        'duty'    => [ 'label' => 'Oras ng Tungkulin',  'icon' => '&#128337;' ],
        'payroll' => [ 'label' => 'Suweldo',            'icon' => '&#128176;' ],
    ];
}
function pljr_sanitize_tab( $value ) {
    $tabs = array_keys( pljr_dashboard_tabs() );
    return in_array( $value, $tabs, true ) ? $value : $tabs[0];
}

/* -------------------------------------------------------------------------
   Discord linking — lets a job-role account clock in/out from Discord
   instead of the web portal. A member connects their Discord account once
   (OAuth2, so it can't be spoofed), then the bot resolves a Discord ID back
   to this WP user on every /timein, /timeout, /dutystatus command.
------------------------------------------------------------------------- */
function pljr_get_user_by_discord_id( $discord_id ) {
    $users = get_users( [
        'meta_key'   => 'pljr_discord_id',
        'meta_value' => $discord_id,
        'number'     => 1,
    ] );
    return $users ? $users[0] : null;
}
function pljr_discord_connect_url() {
    if ( ! defined( 'PLJR_DISCORD_CLIENT_ID' ) || ! PLJR_DISCORD_CLIENT_ID ) return null;
    return 'https://discord.com/oauth2/authorize?' . http_build_query( [
        'client_id'     => PLJR_DISCORD_CLIENT_ID,
        'redirect_uri'  => PLJR_DISCORD_REDIRECT_URI,
        'response_type' => 'code',
        'scope'         => 'identify',
        'state'         => wp_create_nonce( 'pljr_discord_oauth' ),
    ] );
}
/**
 * Connect/disconnect status + button for the currently logged-in user.
 * Shown from both the member panel and the Head's own duty widget.
 */
function pljr_render_discord_link( $user_id ) {
    $discord_id       = get_user_meta( $user_id, 'pljr_discord_id', true );
    $discord_username = get_user_meta( $user_id, 'pljr_discord_username', true );

    echo '<div class="pljr-discord-link">';
    if ( $discord_id ) {
        echo '<span class="pljr-discord-status">&#128172; Naka-connect sa Discord bilang <strong>' . esc_html( $discord_username ?: $discord_id ) . '</strong></span>';
        echo '<form method="post" class="pljr-inline-form">';
        echo '<input type="hidden" name="pljr_action" value="disconnect_discord">';
        wp_nonce_field( 'pljr_disconnect_discord', 'pljr_disconnect_discord_nonce' );
        echo '<button type="submit" class="pljr-btn-sm pljr-btn-danger">I-disconnect</button></form>';
    } else {
        $url = pljr_discord_connect_url();
        if ( $url ) {
            echo '<a class="pljr-btn pljr-btn-sm" href="' . esc_url( $url ) . '">I-connect ang Discord</a>';
            echo '<p class="pljr-sub">Kapag naka-connect, pwede ka nang mag-time in/out gamit ang /timein at /timeout sa Discord.</p>';
        } else {
            echo '<p class="pljr-empty">Hindi pa naka-configure ang Discord integration.</p>';
        }
    }
    echo '</div>';
}
/** Shared message strings for the Discord OAuth round trip's error codes. */
function pljr_discord_error_messages() {
    return [
        'discord_auth'   => 'Kailangan mong naka-login para i-connect ang Discord.',
        'discord_nonce'  => 'Nag-expire ang koneksyon sa Discord, subukan ulit.',
        'discord_config' => 'Hindi pa naka-configure ang Discord integration ng site.',
        'discord_token'  => 'Nabigo ang pag-connect sa Discord. Subukan ulit.',
        'discord_me'     => 'Hindi makuha ang Discord account mo. Subukan ulit.',
        'discord_taken'  => 'Naka-link na ang Discord account na iyan sa ibang user.',
    ];
}

/**
 * Discord's redirect back after the user approves the OAuth consent screen.
 * Handled on a plain page load (not the REST API) so the browser's normal
 * WP login cookie just works — no separate nonce header to juggle.
 */
add_action( 'init', function () {
    if ( ! isset( $_GET['pljr_discord_callback'], $_GET['code'], $_GET['state'] ) ) return;

    $portal_url = home_url( '/job-portal/' );

    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_auth', $portal_url ) );
        exit;
    }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['state'] ) ), 'pljr_discord_oauth' ) ) {
        wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_nonce', $portal_url ) );
        exit;
    }
    if ( ! defined( 'PLJR_DISCORD_CLIENT_ID' ) || ! PLJR_DISCORD_CLIENT_ID || ! defined( 'PLJR_DISCORD_CLIENT_SECRET' ) || ! PLJR_DISCORD_CLIENT_SECRET ) {
        wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_config', $portal_url ) );
        exit;
    }

    $token_response = wp_remote_post( 'https://discord.com/api/oauth2/token', [
        'body' => [
            'client_id'     => PLJR_DISCORD_CLIENT_ID,
            'client_secret' => PLJR_DISCORD_CLIENT_SECRET,
            'grant_type'    => 'authorization_code',
            'code'          => sanitize_text_field( wp_unslash( $_GET['code'] ) ),
            'redirect_uri'  => PLJR_DISCORD_REDIRECT_URI,
        ],
    ] );
    $token_body   = ! is_wp_error( $token_response ) ? json_decode( wp_remote_retrieve_body( $token_response ), true ) : null;
    $access_token = $token_body['access_token'] ?? '';
    if ( ! $access_token ) {
        wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_token', $portal_url ) );
        exit;
    }

    $me_response = wp_remote_get( 'https://discord.com/api/users/@me', [
        'headers' => [ 'Authorization' => 'Bearer ' . $access_token ],
    ] );
    $me         = ! is_wp_error( $me_response ) ? json_decode( wp_remote_retrieve_body( $me_response ), true ) : null;
    $discord_id = isset( $me['id'] ) ? sanitize_text_field( $me['id'] ) : '';
    if ( ! $discord_id ) {
        wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_me', $portal_url ) );
        exit;
    }

    $existing = pljr_get_user_by_discord_id( $discord_id );
    if ( $existing && $existing->ID !== get_current_user_id() ) {
        wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_taken', $portal_url ) );
        exit;
    }

    update_user_meta( get_current_user_id(), 'pljr_discord_id', $discord_id );
    update_user_meta( get_current_user_id(), 'pljr_discord_username', isset( $me['username'] ) ? sanitize_text_field( $me['username'] ) : '' );
    wp_safe_redirect( add_query_arg( 'pljr_discord_linked', '1', $portal_url ) );
    exit;
} );

/* -------------------------------------------------------------------------
   REST API for the Discord bot — /timein, /timeout, /dutystatus resolve a
   Discord ID to a WP job account here, then reuse the same clock-in/out
   helpers the web portal uses so both surfaces share one duty log.
------------------------------------------------------------------------- */
function pljr_bot_auth( WP_REST_Request $request ) {
    if ( ! defined( 'PLJR_BOT_API_KEY' ) || PLJR_BOT_API_KEY === '' ) return false;
    $key = $request->get_header( 'x-api-key' );
    return is_string( $key ) && hash_equals( PLJR_BOT_API_KEY, $key );
}
/** Resolves the discord_id request param to [ WP_User, job slug ], or a WP_Error. */
function pljr_bot_resolve_user( WP_REST_Request $request ) {
    $discord_id = (string) $request->get_param( 'discord_id' );
    if ( ! preg_match( '/^\d{15,25}$/', $discord_id ) ) {
        return new WP_Error( 'missing_discord_id', 'A valid discord_id is required.', [ 'status' => 400 ] );
    }
    $user = pljr_get_user_by_discord_id( $discord_id );
    if ( ! $user ) {
        return new WP_Error( 'not_linked', 'No Pulang Lupa account is linked to this Discord account.', [ 'status' => 404 ] );
    }
    $job = pljr_get_user_job( $user );
    if ( ! $job ) {
        return new WP_Error( 'no_job', 'The linked account has no job role.', [ 'status' => 409 ] );
    }
    return [ $user, $job ];
}
add_action( 'rest_api_init', function () {
    register_rest_route( 'pljr/v1', '/duty/clock-in', [
        'methods'             => 'POST',
        'permission_callback' => 'pljr_bot_auth',
        'callback'            => function ( WP_REST_Request $request ) {
            $resolved = pljr_bot_resolve_user( $request );
            if ( is_wp_error( $resolved ) ) return $resolved;
            [ $user, $job ] = $resolved;
            if ( pljr_get_open_session( $user->ID ) ) {
                return new WP_REST_Response( [ 'ok' => false, 'message' => 'Already clocked in.' ], 409 );
            }
            pljr_clock_in( $user->ID, $job );
            return new WP_REST_Response( [
                'ok'           => true,
                'display_name' => $user->display_name,
                'job'          => pljr_jobs()[ $job ] ?? $job,
            ], 200 );
        },
    ] );
    register_rest_route( 'pljr/v1', '/duty/clock-out', [
        'methods'             => 'POST',
        'permission_callback' => 'pljr_bot_auth',
        'callback'            => function ( WP_REST_Request $request ) {
            $resolved = pljr_bot_resolve_user( $request );
            if ( is_wp_error( $resolved ) ) return $resolved;
            [ $user, $job ] = $resolved;
            $open = pljr_get_open_session( $user->ID );
            if ( ! $open ) {
                return new WP_REST_Response( [ 'ok' => false, 'message' => 'Not currently clocked in.' ], 409 );
            }
            $minutes = max( 0, round( ( time() - strtotime( $open->time_in ) ) / 60 ) );
            pljr_clock_out( $user->ID );
            return new WP_REST_Response( [
                'ok'           => true,
                'display_name' => $user->display_name,
                'job'          => pljr_jobs()[ $job ] ?? $job,
                'duration'     => pljr_format_duration( $minutes ),
            ], 200 );
        },
    ] );
    register_rest_route( 'pljr/v1', '/duty/status', [
        'methods'             => 'GET',
        'permission_callback' => 'pljr_bot_auth',
        'callback'            => function ( WP_REST_Request $request ) {
            $resolved = pljr_bot_resolve_user( $request );
            if ( is_wp_error( $resolved ) ) return $resolved;
            [ $user, $job ] = $resolved;
            $open = pljr_get_open_session( $user->ID );
            return new WP_REST_Response( [
                'ok'           => true,
                'display_name' => $user->display_name,
                'job'          => pljr_jobs()[ $job ] ?? $job,
                'on_duty'      => (bool) $open,
                'since'        => $open ? mysql2date( 'c', $open->time_in ) : null,
            ], 200 );
        },
    ] );
} );

/* -------------------------------------------------------------------------
   Lock job-role accounts (head or member) out of wp-admin entirely.
   Real administrators (manage_options) are untouched.
------------------------------------------------------------------------- */
add_action( 'admin_init', function () {
    if ( wp_doing_ajax() ) return;
    if ( ! is_user_logged_in() ) return;
    if ( current_user_can( 'manage_options' ) ) return;
    if ( pljr_get_user_job() === null ) return;
    wp_safe_redirect( home_url( '/job-portal/' ) );
    exit;
} );
add_filter( 'show_admin_bar', function ( $show ) {
    if ( pljr_get_user_job() !== null && ! current_user_can( 'manage_options' ) ) return false;
    return $show;
} );

/* -------------------------------------------------------------------------
   Front-end form handling (Post/Redirect/Get pattern, runs early on init)
------------------------------------------------------------------------- */
add_action( 'init', function () {
    if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) !== 'POST' || ! isset( $_POST['pljr_action'] ) ) {
        return;
    }
    $action = sanitize_key( $_POST['pljr_action'] );

    // Every form on the dashboard carries which tab (and, where relevant,
    // which date range) it was submitted from, so the Head lands back on
    // the same view instead of being reset to the Roster tab each time.
    $portal_url = add_query_arg( 'ptab', pljr_sanitize_tab( sanitize_key( $_POST['pljr_tab'] ?? '' ) ), home_url( '/job-portal/' ) );
    if ( isset( $_POST['pljr_from'] ) ) $portal_url = add_query_arg( 'from', pljr_sanitize_date( wp_unslash( $_POST['pljr_from'] ), '' ), $portal_url );
    if ( isset( $_POST['pljr_to'] ) )   $portal_url = add_query_arg( 'to', pljr_sanitize_date( wp_unslash( $_POST['pljr_to'] ), '' ), $portal_url );

    if ( $action === 'login' ) {
        if ( ! isset( $_POST['pljr_login_nonce'] ) || ! wp_verify_nonce( $_POST['pljr_login_nonce'], 'pljr_login' ) ) {
            wp_safe_redirect( add_query_arg( 'pljr_error', 'nonce', $portal_url ) );
            exit;
        }
        $user = wp_signon( [
            'user_login'    => sanitize_text_field( wp_unslash( $_POST['pljr_username'] ?? '' ) ),
            'user_password' => (string) ( $_POST['pljr_password'] ?? '' ),
            'remember'      => true,
        ], is_ssl() );
        if ( is_wp_error( $user ) ) {
            wp_safe_redirect( add_query_arg( 'pljr_error', 'login', $portal_url ) );
            exit;
        }
        wp_safe_redirect( $portal_url );
        exit;
    }

    if ( $action === 'disconnect_discord' ) {
        if ( ! is_user_logged_in() ) {
            wp_safe_redirect( add_query_arg( 'pljr_error', 'discord_auth', $portal_url ) );
            exit;
        }
        check_admin_referer( 'pljr_disconnect_discord', 'pljr_disconnect_discord_nonce' );
        delete_user_meta( get_current_user_id(), 'pljr_discord_id' );
        delete_user_meta( get_current_user_id(), 'pljr_discord_username' );
        wp_safe_redirect( $portal_url );
        exit;
    }

    if ( in_array( $action, [ 'clock_in', 'clock_out' ], true ) ) {
        $job = pljr_get_user_job();
        if ( ! is_user_logged_in() || $job === null ) {
            wp_safe_redirect( add_query_arg( 'pljr_error', 'auth', $portal_url ) );
            exit;
        }
        check_admin_referer( 'pljr_clock', 'pljr_clock_nonce' );
        $uid = get_current_user_id();
        if ( $action === 'clock_in' ) {
            pljr_clock_in( $uid, $job );
        } else {
            pljr_clock_out( $uid );
        }
        wp_safe_redirect( $portal_url );
        exit;
    }

    if ( in_array( $action, [ 'add_member', 'remove_member', 'reset_password', 'set_rate' ], true ) ) {
        if ( ! is_user_logged_in() || ! pljr_user_is_head() ) {
            wp_safe_redirect( add_query_arg( 'pljr_error', 'auth', $portal_url ) );
            exit;
        }
        // The job is derived server-side from the logged-in Head — never from client input.
        $job = pljr_get_user_job();

        if ( $action === 'add_member' ) {
            check_admin_referer( 'pljr_add_member', 'pljr_add_member_nonce' );

            $username = isset( $_POST['pljr_new_username'] ) ? sanitize_user( wp_unslash( $_POST['pljr_new_username'] ), true ) : '';
            $display  = isset( $_POST['pljr_new_display'] ) ? sanitize_text_field( wp_unslash( $_POST['pljr_new_display'] ) ) : '';
            $email_in = isset( $_POST['pljr_new_email'] ) ? sanitize_email( wp_unslash( $_POST['pljr_new_email'] ) ) : '';

            if ( ! $username || ! validate_username( $username ) || strlen( $username ) < 3 ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'username', $portal_url ) );
                exit;
            }
            if ( username_exists( $username ) ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'exists', $portal_url ) );
                exit;
            }
            $email = $email_in ?: ( $username . '@pulangluparp.local' );
            if ( ! is_email( $email ) || email_exists( $email ) ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'email', $portal_url ) );
                exit;
            }

            $password = wp_generate_password( 14, true, false );
            $new_id   = wp_insert_user( [
                'user_login'   => $username,
                'user_pass'    => $password,
                'user_email'   => $email,
                'display_name' => $display ?: $username,
                'role'         => $job,
            ] );
            if ( is_wp_error( $new_id ) ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'create', $portal_url ) );
                exit;
            }

            $token = wp_generate_password( 20, false );
            set_transient( 'pljr_reveal_' . $token, [ 'username' => $username, 'password' => $password ], 60 );
            wp_safe_redirect( add_query_arg( 'pljr_reveal', $token, $portal_url ) );
            exit;
        }

        if ( $action === 'remove_member' ) {
            check_admin_referer( 'pljr_remove_member', 'pljr_remove_member_nonce' );
            $target_id = isset( $_POST['pljr_user_id'] ) ? absint( $_POST['pljr_user_id'] ) : 0;
            $target    = $target_id ? get_user_by( 'id', $target_id ) : false;
            if ( ! $target || ! in_array( $job, (array) $target->roles, true ) ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'target', $portal_url ) );
                exit;
            }
            require_once ABSPATH . 'wp-admin/includes/user.php';
            wp_delete_user( $target_id );
            wp_safe_redirect( add_query_arg( 'pljr_removed', '1', $portal_url ) );
            exit;
        }

        if ( $action === 'reset_password' ) {
            check_admin_referer( 'pljr_reset_password', 'pljr_reset_password_nonce' );
            $target_id = isset( $_POST['pljr_user_id'] ) ? absint( $_POST['pljr_user_id'] ) : 0;
            $target    = $target_id ? get_user_by( 'id', $target_id ) : false;
            if ( ! $target || ! in_array( $job, (array) $target->roles, true ) ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'target', $portal_url ) );
                exit;
            }
            $password = wp_generate_password( 14, true, false );
            wp_set_password( $password, $target_id );
            $token = wp_generate_password( 20, false );
            set_transient( 'pljr_reveal_' . $token, [ 'username' => $target->user_login, 'password' => $password ], 60 );
            wp_safe_redirect( add_query_arg( 'pljr_reveal', $token, $portal_url ) );
            exit;
        }

        if ( $action === 'set_rate' ) {
            check_admin_referer( 'pljr_set_rate', 'pljr_set_rate_nonce' );
            $target_id = isset( $_POST['pljr_user_id'] ) ? absint( $_POST['pljr_user_id'] ) : 0;
            $target    = $target_id ? get_user_by( 'id', $target_id ) : false;
            if ( ! $target || ! in_array( $job, (array) $target->roles, true ) ) {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'target', $portal_url ) );
                exit;
            }
            $rate_raw = isset( $_POST['pljr_rate'] ) ? wp_unslash( $_POST['pljr_rate'] ) : '';
            if ( $rate_raw === '' ) {
                delete_user_meta( $target_id, 'pljr_hourly_rate' );
            } elseif ( is_numeric( $rate_raw ) && (float) $rate_raw >= 0 ) {
                pljr_set_user_rate( $target_id, (float) $rate_raw );
            } else {
                wp_safe_redirect( add_query_arg( 'pljr_error', 'rate', $portal_url ) );
                exit;
            }
            wp_safe_redirect( add_query_arg( 'pljr_rate_set', '1', $portal_url ) );
            exit;
        }
    }
} );

/* -------------------------------------------------------------------------
   [job_portal] shortcode — login form / Head roster manager / member panel
------------------------------------------------------------------------- */
function pljr_shortcode() {
    wp_enqueue_style( 'pljr-style', PLJR_URL . 'assets/portal.css', [], PLJR_VERSION );

    $error        = isset( $_GET['pljr_error'] ) ? sanitize_key( $_GET['pljr_error'] ) : '';
    $reveal_token = isset( $_GET['pljr_reveal'] ) ? sanitize_text_field( wp_unslash( $_GET['pljr_reveal'] ) ) : '';
    $reveal       = null;
    if ( $reveal_token ) {
        $reveal = get_transient( 'pljr_reveal_' . $reveal_token );
        if ( $reveal ) delete_transient( 'pljr_reveal_' . $reveal_token );
    }
    $removed        = isset( $_GET['pljr_removed'] );
    $rate_set       = isset( $_GET['pljr_rate_set'] );
    $discord_linked = isset( $_GET['pljr_discord_linked'] );
    list( $default_from, $default_to ) = pljr_default_range();
    $from = pljr_sanitize_date( $_GET['from'] ?? null, $default_from );
    $to   = pljr_sanitize_date( $_GET['to'] ?? null, $default_to );
    $tab  = pljr_sanitize_tab( sanitize_key( $_GET['ptab'] ?? '' ) );

    $is_head = is_user_logged_in() && pljr_user_is_head();

    ob_start();
    echo '<div class="pljr-portal' . ( $is_head ? ' pljr-wide' : '' ) . '">';

    if ( ! is_user_logged_in() ) {
        pljr_render_login( $error );
    } else {
        $job = pljr_get_user_job();
        if ( $job === null ) {
            echo '<div class="pljr-panel"><p>' . esc_html__( 'Naka-login ka, ngunit walang trabahong naka-link sa account na ito.', 'pulang-lupa' ) . '</p>';
            echo '<p><a class="pljr-btn pljr-btn-ghost" href="' . esc_url( wp_logout_url( home_url( '/job-portal/' ) ) ) . '">' . esc_html__( 'Mag-logout', 'pulang-lupa' ) . '</a></p></div>';
        } elseif ( $is_head ) {
            pljr_render_head_dashboard( $job, $reveal, $removed, $error, $from, $to, $rate_set, $tab, $discord_linked );
        } else {
            pljr_render_member_panel( $job, $from, $to, $error, $discord_linked );
        }
    }

    echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'job_portal', 'pljr_shortcode' );

function pljr_render_login( $error ) {
    $messages = [
        'login' => 'Maling username o password.',
        'nonce' => 'Nag-expire ang form, subukan ulit.',
        'auth'  => 'Kailangan mong maging Head para dito.',
    ];
    echo '<div class="pljr-panel pljr-login">';
    echo '<h2>Job Portal</h2>';
    echo '<p class="pljr-sub">Mag-login gamit ang account ng iyong trabaho.</p>';
    if ( $error && isset( $messages[ $error ] ) ) {
        echo '<p class="pljr-error">' . esc_html( $messages[ $error ] ) . '</p>';
    }
    echo '<form method="post" class="pljr-form">';
    echo '<input type="hidden" name="pljr_action" value="login">';
    wp_nonce_field( 'pljr_login', 'pljr_login_nonce' );
    echo '<label>Username<input type="text" name="pljr_username" required autocomplete="username"></label>';
    echo '<label>Password<input type="password" name="pljr_password" required autocomplete="current-password"></label>';
    echo '<button type="submit" class="pljr-btn">Mag-login</button>';
    echo '</form></div>';
}

/**
 * Time-in/time-out control + this user's own recent sessions. Shared by both
 * the Head's own clock (Duty tab) and the Member panel. $tab is null for the
 * Member panel (no tabs there); when set, it's carried in the clock form so
 * the Head lands back on the same tab after clocking in/out.
 */
function pljr_render_duty_widget( $user_id, $job, $from, $to, $tab = null ) {
    $open = pljr_get_open_session( $user_id );
    $hidden_state = ( $tab ? '<input type="hidden" name="pljr_tab" value="' . esc_attr( $tab ) . '">' : '' )
        . '<input type="hidden" name="pljr_from" value="' . esc_attr( $from ) . '">'
        . '<input type="hidden" name="pljr_to" value="' . esc_attr( $to ) . '">';

    if ( $open ) {
        $since = mysql2date( 'M j, g:i A', $open->time_in );
        echo '<div class="pljr-duty-status pljr-duty-on"><span class="pljr-duty-dot"></span>Naka-duty simula ' . esc_html( $since ) . '</div>';
        echo '<form method="post" class="pljr-inline-form">';
        echo '<input type="hidden" name="pljr_action" value="clock_out">' . $hidden_state;
        wp_nonce_field( 'pljr_clock', 'pljr_clock_nonce' );
        echo '<button type="submit" class="pljr-btn pljr-btn-danger">Mag-Time Out</button></form>';
    } else {
        echo '<div class="pljr-duty-status pljr-duty-off"><span class="pljr-duty-dot"></span>Wala sa duty</div>';
        echo '<form method="post" class="pljr-inline-form">';
        echo '<input type="hidden" name="pljr_action" value="clock_in">' . $hidden_state;
        wp_nonce_field( 'pljr_clock', 'pljr_clock_nonce' );
        echo '<button type="submit" class="pljr-btn">Mag-Time In</button></form>';
    }

    echo '<h4 class="pljr-subhead">Ang mga sarili kong oras</h4>';
    $sessions = pljr_duty_sessions( $user_id, $from, $to, 15 );
    if ( $sessions ) {
        echo '<table class="pljr-log-table"><thead><tr><th>Petsa</th><th>Time In</th><th>Time Out</th><th>Tagal</th></tr></thead><tbody>';
        foreach ( $sessions as $s ) {
            $tout = $s->time_out ? mysql2date( 'g:i A', $s->time_out ) : '&mdash;';
            $dur  = $s->duration_minutes !== null ? pljr_format_duration( $s->duration_minutes ) : '(naka-duty)';
            echo '<tr><td>' . esc_html( mysql2date( 'M j', $s->time_in ) ) . '</td><td>' . esc_html( mysql2date( 'g:i A', $s->time_in ) ) . '</td><td>' . $tout . '</td><td>' . esc_html( $dur ) . '</td></tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p class="pljr-empty">Walang naka-log na oras sa hanay na ito.</p>';
    }
}

function pljr_render_range_form( $from, $to, $tab = null ) {
    echo '<form method="get" class="pljr-range-form">';
    if ( $tab ) echo '<input type="hidden" name="ptab" value="' . esc_attr( $tab ) . '">';
    echo '<label>Mula<input type="date" name="from" value="' . esc_attr( $from ) . '"></label>';
    echo '<label>Hanggang<input type="date" name="to" value="' . esc_attr( $to ) . '"></label>';
    echo '<button type="submit" class="pljr-btn-sm">Ipakita</button>';
    echo '</form>';
}

function pljr_render_dashboard_tabs( $active_tab, $from, $to, $counts = [] ) {
    echo '<nav class="pljr-tabs">';
    foreach ( pljr_dashboard_tabs() as $slug => $info ) {
        $url = add_query_arg( [ 'ptab' => $slug, 'from' => $from, 'to' => $to ], home_url( '/job-portal/' ) );
        $count = isset( $counts[ $slug ] ) ? ' <span class="pljr-count">' . (int) $counts[ $slug ] . '</span>' : '';
        echo '<a class="pljr-tab' . ( $slug === $active_tab ? ' active' : '' ) . '" href="' . esc_url( $url ) . '">';
        echo '<span class="pljr-card-icon">' . $info['icon'] . '</span>' . esc_html( $info['label'] ) . $count . '</a>';
    }
    echo '</nav>';
}

function pljr_render_head_dashboard( $job, $reveal, $removed, $error, $from, $to, $rate_set, $tab, $discord_linked = false ) {
    $jobs    = pljr_jobs();
    $label   = $jobs[ $job ] ?? $job;
    $members = get_users( [ 'role' => $job, 'orderby' => 'display_name' ] );

    echo '<div class="pljr-dash-head"><h2><span class="pljr-card-icon">&#9878;</span>' . esc_html( $label ) . ' Head</h2>';
    echo '<a class="pljr-btn pljr-btn-ghost pljr-btn-sm" href="' . esc_url( wp_logout_url( home_url( '/job-portal/' ) ) ) . '">Mag-logout</a></div>';

    /* Notices — shown once above the tabs, not tied visually to one section */
    if ( $reveal ) {
        echo '<div class="pljr-reveal"><strong>Nagawa / na-reset ang account:</strong>';
        echo '<div class="pljr-reveal-row"><span>Username</span><code>' . esc_html( $reveal['username'] ) . '</code></div>';
        echo '<div class="pljr-reveal-row"><span>Password</span><code>' . esc_html( $reveal['password'] ) . '</code></div>';
        echo '<p class="pljr-warn">Isulat ito ngayon &mdash; hindi na ito muling ipapakita.</p></div>';
    }
    if ( $removed ) echo '<p class="pljr-ok">Naalis ang miyembro.</p>';
    if ( $rate_set ) echo '<p class="pljr-ok">Na-update ang rate.</p>';
    if ( $discord_linked ) echo '<p class="pljr-ok">Na-connect ang Discord account mo.</p>';
    $errors = array_merge( pljr_discord_error_messages(), [
        'exists'   => 'Ginagamit na ang username na iyan.',
        'username' => 'Hindi valid ang username (3&ndash;20 chars, letters/numbers).',
        'email'    => 'Ginagamit na o hindi valid ang email.',
        'create'   => 'Hindi nagawa ang account.',
        'target'   => 'Hindi mo mapamahalaan ang account na iyon.',
        'rate'     => 'Hindi valid ang rate na inilagay.',
    ] );
    if ( $error && isset( $errors[ $error ] ) ) {
        echo '<p class="pljr-error">' . esc_html( $errors[ $error ] ) . '</p>';
    }

    pljr_render_dashboard_tabs( $tab, $from, $to, [ 'roster' => count( $members ) ] );

    echo '<section class="pljr-card pljr-card-wide">';

    if ( $tab === 'roster' ) {

        echo '<div class="pljr-card-head"><h3><span class="pljr-card-icon">&#128101;</span>Mga Kasapi <span class="pljr-count">' . count( $members ) . '</span></h3></div>';
        if ( ! $members ) {
            echo '<p class="pljr-empty">Wala pang kasapi. Magdagdag sa ibaba.</p>';
        } else {
            echo '<ul class="pljr-roster">';
            foreach ( $members as $m ) {
                echo '<li class="pljr-roster-row">';
                echo '<div class="pljr-roster-id"><span class="pljr-roster-name">' . esc_html( $m->display_name ) . '</span>';
                echo '<span class="pljr-roster-login">@' . esc_html( $m->user_login ) . '</span></div>';
                echo '<div class="pljr-roster-actions">';
                echo '<form method="post" class="pljr-inline-form"><input type="hidden" name="pljr_action" value="reset_password"><input type="hidden" name="pljr_user_id" value="' . esc_attr( $m->ID ) . '"><input type="hidden" name="pljr_tab" value="roster">';
                wp_nonce_field( 'pljr_reset_password', 'pljr_reset_password_nonce' );
                echo '<button type="submit" class="pljr-btn-sm">I-reset ang password</button></form>';
                echo '<form method="post" class="pljr-inline-form" onsubmit="return confirm(\'Alisin si ' . esc_js( $m->display_name ) . '?\');"><input type="hidden" name="pljr_action" value="remove_member"><input type="hidden" name="pljr_user_id" value="' . esc_attr( $m->ID ) . '"><input type="hidden" name="pljr_tab" value="roster">';
                wp_nonce_field( 'pljr_remove_member', 'pljr_remove_member_nonce' );
                echo '<button type="submit" class="pljr-btn-sm pljr-btn-danger">Alisin</button></form>';
                echo '</div></li>';
            }
            echo '</ul>';
        }
        echo '<details class="pljr-add-details"><summary>+ Magdagdag ng Bagong Kasapi</summary>';
        echo '<form method="post" class="pljr-form pljr-form-row">';
        echo '<input type="hidden" name="pljr_action" value="add_member"><input type="hidden" name="pljr_tab" value="roster">';
        wp_nonce_field( 'pljr_add_member', 'pljr_add_member_nonce' );
        echo '<label>Username<input type="text" name="pljr_new_username" required pattern="[A-Za-z0-9_\-.]{3,20}"></label>';
        echo '<label>Pangalan (sa RP)<input type="text" name="pljr_new_display"></label>';
        echo '<label>Email (opsyonal)<input type="email" name="pljr_new_email"></label>';
        echo '<button type="submit" class="pljr-btn">Gumawa ng Account</button>';
        echo '</form></details>';

    } elseif ( $tab === 'duty' ) {

        echo '<div class="pljr-card-head"><h3><span class="pljr-card-icon">&#128337;</span>Oras ng Tungkulin</h3>';
        pljr_render_range_form( $from, $to, 'duty' );
        echo '</div>';

        echo '<h4 class="pljr-subhead">Ang oras mo</h4>';
        pljr_render_discord_link( get_current_user_id() );
        pljr_render_duty_widget( get_current_user_id(), $job, $from, $to, 'duty' );

        echo '<h4 class="pljr-subhead">Buong Koponan &mdash; Talaan ng Oras</h4>';
        if ( ! $members ) {
            echo '<p class="pljr-empty">Wala pang kasapi.</p>';
        } else {
            $names = [];
            foreach ( $members as $m ) $names[ $m->ID ] = $m->display_name;
            $names[ get_current_user_id() ] = wp_get_current_user()->display_name . ' (Head)';

            $log = pljr_duty_log_for_job( $job, $from, $to, 200 );
            if ( $log ) {
                echo '<table class="pljr-log-table"><thead><tr><th>Petsa</th><th>Kasapi</th><th>Time In</th><th>Time Out</th><th>Tagal</th></tr></thead><tbody>';
                foreach ( $log as $s ) {
                    $who  = $names[ (int) $s->user_id ] ?? ( 'User #' . $s->user_id );
                    $tout = $s->time_out ? mysql2date( 'g:i A', $s->time_out ) : '&mdash;';
                    $dur  = $s->duration_minutes !== null ? pljr_format_duration( $s->duration_minutes ) : '(naka-duty)';
                    echo '<tr><td>' . esc_html( mysql2date( 'M j', $s->time_in ) ) . '</td><td>' . esc_html( $who ) . '</td><td>' . esc_html( mysql2date( 'g:i A', $s->time_in ) ) . '</td><td>' . $tout . '</td><td>' . esc_html( $dur ) . '</td></tr>';
                }
                echo '</tbody></table>';
                if ( count( $log ) >= 200 ) {
                    echo '<p class="pljr-warn">Ipinapakita lang ang pinakabagong 200 na entry &mdash; paliitin ang hanay ng petsa para makita ang iba.</p>';
                }
            } else {
                echo '<p class="pljr-empty">Walang naka-log na oras sa hanay na ito.</p>';
            }
        }

    } else { // payroll

        echo '<div class="pljr-card-head"><h3><span class="pljr-card-icon">&#128176;</span>Suweldo ng mga Kasapi</h3>';
        pljr_render_range_form( $from, $to, 'payroll' );
        echo '</div>';

        if ( ! $members ) {
            echo '<p class="pljr-empty">Wala pang kasapi na susuwelduhan.</p>';
        } else {
            $totals = pljr_duty_totals( $job, $from, $to );
            echo '<ul class="pljr-pay-list">';
            foreach ( $members as $m ) {
                $minutes = $totals[ $m->ID ] ?? 0;
                $hours   = $minutes / 60;
                $rate    = pljr_user_rate( $m->ID );
                $salary  = $rate !== null ? round( $rate * $hours, 2 ) : null;

                echo '<li class="pljr-pay-row">';
                echo '<div class="pljr-pay-cell pljr-pay-id"><span class="pljr-pay-name">' . esc_html( $m->display_name ) . '</span><span class="pljr-roster-login">@' . esc_html( $m->user_login ) . '</span></div>';
                echo '<div class="pljr-pay-cell"><span class="pljr-pay-label">Oras</span><span class="pljr-pay-value">' . esc_html( pljr_format_duration( $minutes ) ) . '</span></div>';
                echo '<div class="pljr-pay-cell"><span class="pljr-pay-label">Rate / Oras (&#8369;)</span><form method="post" class="pljr-rate-form">';
                echo '<input type="hidden" name="pljr_action" value="set_rate"><input type="hidden" name="pljr_tab" value="payroll">';
                echo '<input type="hidden" name="pljr_user_id" value="' . esc_attr( $m->ID ) . '">';
                echo '<input type="hidden" name="pljr_from" value="' . esc_attr( $from ) . '">';
                echo '<input type="hidden" name="pljr_to" value="' . esc_attr( $to ) . '">';
                wp_nonce_field( 'pljr_set_rate', 'pljr_set_rate_nonce' );
                echo '<input type="number" step="0.01" min="0" name="pljr_rate" placeholder="&mdash;" value="' . ( $rate !== null ? esc_attr( $rate ) : '' ) . '">';
                echo '<button type="submit" class="pljr-btn-sm">I-set</button>';
                echo '</form></div>';
                echo '<div class="pljr-pay-cell"><span class="pljr-pay-label">Kabuuang Suweldo</span><span class="pljr-pay-value pljr-pay-salary">' . ( $salary !== null ? '&#8369;' . esc_html( number_format( $salary, 2 ) ) : '<span class="pljr-empty">wala pang rate</span>' ) . '</span></div>';
                echo '</li>';
            }
            echo '</ul>';
            echo '<p class="pljr-warn">Tingnan ang tab na Oras ng Tungkulin para sa detalyadong log ng bawat kasapi.</p>';
        }
    }

    echo '</section>';
}

function pljr_render_member_panel( $job, $from, $to, $error = '', $discord_linked = false ) {
    $jobs  = pljr_jobs();
    $label = $jobs[ $job ] ?? $job;
    echo '<div class="pljr-panel">';
    echo '<div class="pljr-head-row"><h2>Naka-login bilang ' . esc_html( $label ) . '</h2>';
    echo '<a class="pljr-btn pljr-btn-ghost pljr-btn-sm" href="' . esc_url( wp_logout_url( home_url( '/job-portal/' ) ) ) . '">Mag-logout</a></div>';
    echo '<p><a class="pljr-btn" href="' . esc_url( home_url( '/crafting/' ) ) . '">Buksan ang Crafting Tracker</a></p>';

    if ( $discord_linked ) echo '<p class="pljr-ok">Na-connect ang Discord account mo.</p>';
    $discord_errors = pljr_discord_error_messages();
    if ( $error && isset( $discord_errors[ $error ] ) ) {
        echo '<p class="pljr-error">' . esc_html( $discord_errors[ $error ] ) . '</p>';
    }

    echo '<h3><span class="pljr-card-icon">&#128337;</span>Oras ng Tungkulin</h3>';
    pljr_render_discord_link( get_current_user_id() );
    pljr_render_range_form( $from, $to );
    pljr_render_duty_widget( get_current_user_id(), $job, $from, $to );

    echo '</div>';
}
