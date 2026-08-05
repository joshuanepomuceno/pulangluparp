<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php bloginfo('description'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header">
  <div class="nav-wrapper">

    <!-- Logo -->
    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-logo" aria-label="<?php bloginfo('name'); ?> – Home">
      <?php
      if (has_custom_logo()) {
          the_custom_logo();
      } else {
          $logo_path = get_template_directory_uri() . '/images/logo.png';
          echo '<img src="' . esc_url($logo_path) . '" alt="' . esc_attr(get_bloginfo('name')) . '" width="52" height="52">';
      }
      ?>
    </a>

    <!-- Primary Navigation -->
    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'menu_class'     => 'nav-menu',
        'container'      => false,
        'walker'         => new PLRP_Nav_Walker(),
        'fallback_cb'    => function () {
            echo '<ul class="nav-menu">
                <li><a href="' . esc_url(home_url('/')) . '">Home</a></li>
                <li><a href="#">Rules</a></li>
                <li><a href="#">Factions</a></li>
                <li><a href="#">Apply</a></li>
                <li><a href="' . esc_url(get_theme_mod('plrp_discord_url', '#')) . '" class="nav-discord-btn" target="_blank" rel="noopener">Discord</a></li>
            </ul>';
        },
    ]);
    ?>

    <!-- Mobile toggle -->
    <button class="nav-toggle" aria-label="Toggle Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

  </div>
</header>
