<footer id="site-footer">
  <div class="footer-inner">
    <div class="footer-top">

      <!-- Brand -->
      <div class="footer-brand">
        <?php
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            echo '<img src="' . esc_url(get_template_directory_uri() . '/images/logo.png') . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="footer-logo">';
        }
        ?>
        <p><?php bloginfo('description'); ?><br>
        A serious whitelisted RedM roleplay server set in the 1890s.</p>
      </div>

      <!-- Navigation -->
      <div class="footer-col">
        <h4>Navigation</h4>
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'container'      => false,
            'fallback_cb'    => function () {
                echo '<ul>
                  <li><a href="' . esc_url(home_url('/')) . '">Home</a></li>
                  <li><a href="#">Rules</a></li>
                  <li><a href="#">Factions</a></li>
                  <li><a href="#">Apply</a></li>
                  <li><a href="#">Donate</a></li>
                </ul>';
            },
        ]);
        ?>
      </div>

      <!-- Community -->
      <div class="footer-col">
        <h4>Community</h4>
        <ul>
          <li><a href="<?php echo esc_url(get_theme_mod('plrp_discord_url', '#')); ?>" target="_blank" rel="noopener">Discord Server</a></li>
          <li><a href="#">Forums</a></li>
          <li><a href="#">Staff Team</a></li>
          <li><a href="#">Bug Reports</a></li>
          <li><a href="#">Suggestions</a></li>
        </ul>
      </div>

    </div><!-- .footer-top -->

    <div class="footer-bottom">
      <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved. Not affiliated with Rockstar Games.</p>
      <div class="footer-social">
        <a href="<?php echo esc_url(get_theme_mod('plrp_discord_url', '#')); ?>" target="_blank" rel="noopener" aria-label="Discord">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057c.001.022.015.04.037.05a19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
        </a>
        <a href="#" aria-label="YouTube">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
        </a>
        <a href="#" aria-label="Facebook">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
      </div>
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
