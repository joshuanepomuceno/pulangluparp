<?php
defined('ABSPATH') || exit;

/* --------------------------------------------------
   Theme Setup
-------------------------------------------------- */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 120,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
    add_theme_support('menus');

    register_nav_menus([
        'primary' => __('Primary Navigation', 'pulang-lupa'),
        'footer'  => __('Footer Navigation', 'pulang-lupa'),
    ]);
});

/* --------------------------------------------------
   Custom RP Profession User Roles
   Registered once (guarded by a version option). Bump
   PLRP_ROLES_VERSION to re-apply after changing the list.
-------------------------------------------------- */
define('PLRP_ROLES_VERSION', '1');

function plrp_profession_roles() {
    return [
        'matadero'  => 'Matadero',
        'ensayador' => 'Ensayador',
        'panday'    => 'Panday',
        'doktor'    => 'Doktor',
        'armero'    => 'Armero',
        'katutubo'  => 'Katutubo',
    ];
}

add_action('init', function () {
    if (get_option('plrp_roles_version') === PLRP_ROLES_VERSION) {
        return;
    }
    // Base capabilities: can log in and view their profile, no admin powers.
    $caps = ['read' => true];
    foreach (plrp_profession_roles() as $slug => $label) {
        remove_role($slug);            // ensure label/caps update on version bump
        add_role($slug, $label, $caps);
    }
    update_option('plrp_roles_version', PLRP_ROLES_VERSION);
});

/* --------------------------------------------------
   Enqueue Styles & Scripts
-------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style(
        'pulang-lupa-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Roboto+Slab:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    // Theme stylesheet — version by file mtime so edits bust the browser cache
    $css_path = get_theme_file_path('style.css');
    wp_enqueue_style(
        'pulang-lupa-style',
        get_stylesheet_uri(),
        ['pulang-lupa-fonts'],
        file_exists($css_path) ? filemtime($css_path) : '1.0'
    );

    // Theme JS — versioned by file mtime for the same reason
    $js_path = get_theme_file_path('assets/js/theme.js');
    wp_enqueue_script(
        'pulang-lupa-scripts',
        get_template_directory_uri() . '/assets/js/theme.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : '1.0',
        true
    );
});

/* --------------------------------------------------
   Customizer Options
-------------------------------------------------- */
add_action('customize_register', function ($wp_customize) {
    // Hero Section
    $wp_customize->add_section('plrp_hero', [
        'title'    => 'Hero Section',
        'priority' => 30,
    ]);

    $wp_customize->add_setting('plrp_hero_tagline', [
        'default'           => 'Your story begins in the <span>Pulang Lupa</span>',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('plrp_hero_tagline', [
        'label'   => 'Hero Tagline',
        'section' => 'plrp_hero',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('plrp_hero_bg', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'plrp_hero_bg', [
        'label'   => 'Hero Background Image',
        'section' => 'plrp_hero',
    ]));

    $wp_customize->add_setting('plrp_discord_url', [
        'default'           => 'https://discord.gg/',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('plrp_discord_url', [
        'label'   => 'Discord Invite URL',
        'section' => 'plrp_hero',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('plrp_apply_url', [
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('plrp_apply_url', [
        'label'   => 'Apply / UCP URL',
        'section' => 'plrp_hero',
        'type'    => 'url',
    ]);
});

/* --------------------------------------------------
   Sync the "Discord" primary-nav link to the Customizer's
   Discord Invite URL setting. The nav item itself is a plain
   custom link stored in the DB, so without this it stays frozen
   at whatever URL was typed in when the menu item was created —
   editing the Customizer setting would silently do nothing to it.
-------------------------------------------------- */
add_filter('wp_nav_menu_objects', function ($items, $args) {
    if (($args->theme_location ?? '') !== 'primary') {
        return $items;
    }
    $discord_url = get_theme_mod('plrp_discord_url', '');
    if (empty($discord_url) || $discord_url === '#') {
        return $items;
    }
    foreach ($items as $item) {
        if (strcasecmp($item->title, 'Discord') === 0) {
            $item->url = esc_url($discord_url);
        }
    }
    return $items;
}, 10, 2);

/* --------------------------------------------------
   Helper: Get Hero BG inline style
-------------------------------------------------- */
function plrp_hero_bg_style() {
    $bg = get_theme_mod('plrp_hero_bg', '');
    if ($bg) {
        return 'style="background-image:url(' . esc_url($bg) . ');"';
    }
    // Default to the bundled RDR2 landscape if present
    if ( file_exists( get_template_directory() . '/images/rdr-hero.jpg' ) ) {
        return 'style="background-image:url(' . esc_url( get_template_directory_uri() . '/images/rdr-hero.jpg' ) . ');"';
    }
    // Fallback gradient if no image available
    return 'style="background-image: linear-gradient(135deg, #1a0a0a 0%, #2d1010 30%, #1a1005 60%, #0a0a0a 100%);"';
}

/* --------------------------------------------------
   Disable Gutenberg block styles on front-end
   (keeps our theme styles clean)
-------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}, 100);

/* --------------------------------------------------
   Custom Nav Walker (adds classes)
-------------------------------------------------- */
class PLRP_Nav_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
        $item  = $data_object;
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        $output .= '<li' . $id . $class_names . '>';
        $atts = [
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel'    => !empty($item->xfn) ? $item->xfn : '',
            'href'   => !empty($item->url) ? $item->url : '',
        ];
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value       = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $output .= '<a' . $attributes . '>';
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a></li>';
    }
}
