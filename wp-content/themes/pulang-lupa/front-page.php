<?php
get_header();
$plrp_img   = get_template_directory_uri() . '/images';
$plrp_apply = esc_url( get_theme_mod( 'plrp_apply_url', '#' ) );
$plrp_disco = esc_url( get_theme_mod( 'plrp_discord_url', '#' ) );
?>

<!-- ================================================
     HERO SECTION
================================================ -->
<section id="hero">
  <div class="hero-bg" data-parallax="0.25" data-parallax-scale="1.25" <?php echo plrp_hero_bg_style(); ?>></div>
  <div class="hero-overlay"></div>
  <div class="hero-grain"></div>
  <div class="hero-border-top"></div>
  <div class="hero-border-bottom"></div>

  <div class="hero-content">
    <img
      src="<?php echo esc_url( $plrp_img . '/logo.png' ); ?>"
      alt="<?php bloginfo('name'); ?>"
      class="hero-logo"
      width="280"
      height="280"
    >

    <p class="hero-tagline">
      <?php echo wp_kses_post( get_theme_mod('plrp_hero_tagline', 'Your story begins in <span>Pulang Lupa</span>') ); ?>
    </p>
    <p class="hero-subtagline">
      A serious whitelisted <em>RedM</em> roleplay server &mdash; Est. 1890s
    </p>

    <div class="hero-buttons">
      <a href="<?php echo $plrp_apply; ?>" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Apply Now
      </a>
      <a href="<?php echo $plrp_disco; ?>" class="btn btn-discord" target="_blank" rel="noopener">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057c.001.022.015.04.037.05a19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
        Join Discord
      </a>
      <a href="#handbook" class="btn btn-outline">Read the Handbook</a>
    </div>
  </div>

  <a href="#handbook" class="hero-scroll-down" aria-label="Scroll down">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="6 9 12 15 18 9"></polyline>
    </svg>
  </a>
</section>


<!-- ================================================
     HANDBOOK FLIPBOOK
================================================ -->
<section id="handbook" class="flipbook-section">
  <div class="section-inner">
    <h2 class="section-title">The Pulang Lupa Handbook</h2>
    <p class="section-subtitle"><em>Kasaysayan ng Pulang Lupa</em> &mdash; turn the pages to read the history of the red land</p>
    <div class="section-divider"><span>✦</span></div>

    <div class="flipbook-stage">
      <div class="flipbook" id="plrp-book" aria-label="Kasaysayan ng Pulang Lupa — click a page to turn it">
        <?php
        $book_dir   = $plrp_img . '/handbook';
        $book_total = 20;
        for ( $k = 0; $k < ceil( $book_total / 2 ); $k++ ) :
            $front = $k * 2 + 1;
            $back  = $k * 2 + 2;
        ?>
        <div class="flip-leaf">
          <div class="leaf-face leaf-front book-img">
            <img src="<?php echo esc_url( sprintf( '%s/page-%02d.jpg', $book_dir, $front ) ); ?>" alt="Page <?php echo esc_attr( $front ); ?>"<?php echo $k < 2 ? '' : ' loading="lazy"'; ?> draggable="false">
          </div>
          <?php if ( $back <= $book_total ) : ?>
          <div class="leaf-face leaf-back book-img">
            <img src="<?php echo esc_url( sprintf( '%s/page-%02d.jpg', $book_dir, $back ) ); ?>" alt="Page <?php echo esc_attr( $back ); ?>"<?php echo $k < 1 ? '' : ' loading="lazy"'; ?> draggable="false">
          </div>
          <?php else : ?>
          <div class="leaf-face leaf-back book-img book-endpaper"></div>
          <?php endif; ?>
        </div>
        <?php endfor; ?>
      </div>

      <div class="flipbook-controls">
        <button class="book-btn" id="book-prev" aria-label="Previous page">&lsaquo; Prev</button>
        <span class="book-progress" id="book-progress">Cover</span>
        <button class="book-btn" id="book-next" aria-label="Next page">Next &rsaquo;</button>
      </div>
      <p class="book-hint">Click a page &mdash; or use the &larr; &rarr; arrow keys &mdash; to turn.</p>
    </div>

    <div class="flip-actions">
      <a href="<?php echo esc_url( home_url('/rules/') ); ?>" class="btn btn-primary">Read the Rules</a>
      <a href="<?php echo esc_url( home_url('/factions/') ); ?>" class="btn btn-outline">Factions Guide</a>
      <a href="<?php echo $plrp_disco; ?>" class="btn btn-discord" target="_blank" rel="noopener">Join Discord</a>
    </div>
  </div>
</section>


<!-- ================================================
     ABOUT SECTION
================================================ -->
<section id="about">
  <div class="section-inner">
    <h2 class="section-title">About the Server</h2>
    <div class="section-divider"><span>✦</span></div>

    <div class="about-grid">
      <div class="about-text">
        <p>
          <strong>Pulang Lupa Roleplay</strong> is a serious, whitelisted RedM server set in the rugged frontier of the 1890s.
          We offer a deeply immersive roleplay experience where every decision shapes your legacy on the untamed land.
        </p>
        <p>
          Whether you're a drifting gunslinger, a cunning outlaw, a frontier merchant, or a lawman keeping the peace &mdash;
          <strong>Pulang Lupa</strong> is your story to write.
        </p>
        <p>
          Our <strong>dedicated staff team</strong> enforces strict roleplay standards, ensuring every player experiences
          a rich, authentic Wild West environment. New players go through a thorough whitelist application to maintain quality.
        </p>
        <a href="<?php echo $plrp_apply; ?>" class="btn btn-primary mt-2" style="display:inline-flex;">
          Start Your Journey
        </a>
      </div>

      <div class="about-media">
        <img src="<?php echo esc_url( $plrp_img . '/rdr-about.jpg' ); ?>" alt="A weathered rider on the frontier at dusk" loading="lazy" data-parallax="0.15" data-parallax-scale="1.15">
        <span class="about-media-frame" aria-hidden="true"></span>
      </div>
    </div>

    <div class="about-stats">
      <div class="stat-card">
        <span class="stat-num">1890s</span>
        <span class="stat-label">Era Setting</span>
      </div>
      <div class="stat-card">
        <span class="stat-num">WL</span>
        <span class="stat-label">Whitelisted</span>
      </div>
      <div class="stat-card">
        <span class="stat-num">24/7</span>
        <span class="stat-label">Server Uptime</span>
      </div>
      <div class="stat-card">
        <span class="stat-num">RP</span>
        <span class="stat-label">Serious Roleplay</span>
      </div>
    </div>
  </div>
</section>


<!-- ================================================
     PARALLAX BAND 1
================================================ -->
<section class="parallax-band">
  <div class="parallax-band-bg" data-parallax="0.22" data-parallax-scale="1.3" style="background-image:url('<?php echo esc_url( $plrp_img . '/rdr-parallax-1.jpg' ); ?>')"></div>
  <div class="parallax-band-inner">
    <span class="parallax-quote-mark" aria-hidden="true">&ldquo;</span>
    <p class="parallax-quote">Every reputation is earned, every conflict has lasting effects, and every story becomes part of Pulang Lupa's history.</p>
    <span class="parallax-quote-by">The frontier remembers</span>
  </div>
</section>


<!-- ================================================
     FEATURES SECTION
================================================ -->
<section id="features">
  <div class="section-inner">
    <h2 class="section-title">What We Offer</h2>
    <p class="section-subtitle">Experience the Old West like never before</p>
    <div class="section-divider"><span>✦</span></div>

    <div class="features-grid">
      <div class="feature-card">
        <span class="feature-icon">🤠</span>
        <h3>Immersive Roleplay</h3>
        <p>Strict RP standards enforced by an experienced staff team to ensure quality interactions every session.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">⚖️</span>
        <h3>Law &amp; Order</h3>
        <p>Join the marshal's office, become a bounty hunter, or live outside the law as an outlaw &mdash; your choice defines the frontier.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">🏘️</span>
        <h3>Property &amp; Business</h3>
        <p>Own land, run businesses, trade goods and build your empire in the frontier economy.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">🐎</span>
        <h3>Frontier Life</h3>
        <p>Hunt, fish, farm, and explore the vast wilderness. Build a life on the range or in one of our growing towns.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">🎭</span>
        <h3>Rich Storylines</h3>
        <p>Participate in server-wide events and faction storylines driven by player actions and staff-run narratives.</p>
      </div>
      <div class="feature-card">
        <span class="feature-icon">🛡️</span>
        <h3>Active Staff</h3>
        <p>Our moderation team is always on hand to handle reports, resolve disputes, and run engaging events.</p>
      </div>
    </div>
  </div>
</section>


<!-- ================================================
     PARALLAX BAND 2
================================================ -->
<section class="parallax-band">
  <div class="parallax-band-bg" data-parallax="0.22" data-parallax-scale="1.3" style="background-image:url('<?php echo esc_url( $plrp_img . '/rdr-parallax-2.jpg' ); ?>')"></div>
  <div class="parallax-band-inner">
    <span class="parallax-quote-mark" aria-hidden="true">&ldquo;</span>
    <p class="parallax-quote">Pulang Lupa RP is a Serious Roleplay RedM server inspired by Philippine history, culture, and folklore, set in the fictional nation of Pulang Lupa in 1890. As the nation stands at the crossroads of political change, economic ambition, and social unrest, every citizen has the opportunity to shape its future through meaningful roleplay and immersive storytelling.
</p>
    <span class="parallax-quote-by">Your legend awaits</span>
  </div>
</section>


<!-- ================================================
     HOW TO JOIN
================================================ -->
<section id="join">
  <div class="join-bg-pattern"></div>
  <div class="section-inner">
    <h2 class="section-title">How to Join</h2>
    <p class="section-subtitle">Follow these steps to begin your frontier story</p>
    <div class="section-divider"><span>✦</span></div>

    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h3>Join Discord</h3>
        <p>All announcements, applications, and community discussions happen on our Discord server.</p>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h3>Read the Rules</h3>
        <p>Familiarise yourself with our server rules and roleplay guidelines before applying.</p>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h3>Submit Application</h3>
        <p>Fill out the whitelist application form with your character backstory and RP experience.</p>
      </div>
      <div class="step-card">
        <div class="step-number">4</div>
        <h3>Get Whitelisted</h3>
        <p>Once approved by our team, you'll receive access. Saddle up and ride into Pulang Lupa!</p>
      </div>
    </div>

    <div class="join-cta">
      <a href="<?php echo $plrp_disco; ?>" class="btn btn-discord" target="_blank" rel="noopener">
        Join our Discord
      </a>
      <a href="<?php echo $plrp_apply; ?>" class="btn btn-primary">
        Apply Now
      </a>
    </div>
  </div>
</section>


<!-- ================================================
     RULES PREVIEW
================================================ -->
<section id="rules-preview">
  <div class="section-inner">
    <h2 class="section-title">Core Rules</h2>
    <p class="section-subtitle">A brief overview of what we expect from all players</p>
    <div class="section-divider"><span>✦</span></div>

    <div class="rules-grid">
      <div class="rule-item">
        <span class="rule-icon">🔇</span>
        <div>
          <h4>No Meta-Gaming</h4>
          <p>Never use out-of-character information to influence in-character decisions or actions.</p>
        </div>
      </div>
      <div class="rule-item">
        <span class="rule-icon">💀</span>
        <div>
          <h4>Value Your Life</h4>
          <p>Act as if your character values their life. Do not engage in reckless behaviour without fear of consequence.</p>
        </div>
      </div>
      <div class="rule-item">
        <span class="rule-icon">🎭</span>
        <div>
          <h4>Stay In Character</h4>
          <p>Maintain your character at all times while in-game. Use OOC channels for out-of-character communication.</p>
        </div>
      </div>
      <div class="rule-item">
        <span class="rule-icon">🤝</span>
        <div>
          <h4>Respect All Players</h4>
          <p>Treat all members with respect in and out of character. Harassment or toxic behaviour will not be tolerated.</p>
        </div>
      </div>
      <div class="rule-item">
        <span class="rule-icon">📋</span>
        <div>
          <h4>Follow Backstory</h4>
          <p>Roleplay consistently with your approved character backstory and do not engage in character breaking.</p>
        </div>
      </div>
      <div class="rule-item">
        <span class="rule-icon">🚫</span>
        <div>
          <h4>No Power-Gaming</h4>
          <p>Do not force actions on other players or perform feats impossible within realistic roleplay context.</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-2">
      <a href="<?php echo esc_url( home_url('/rules/') ); ?>" class="btn btn-outline">Read Full Rules</a>
    </div>
  </div>
</section>


<!-- ================================================
     COMMUNITY / DISCORD CTA
================================================ -->
<section id="community">
  <div class="section-inner">
    <h2 class="section-title">Join Our Community</h2>
    <div class="section-divider"><span>✦</span></div>
    <p>Connect with hundreds of players, get updates, apply for whitelist access, and be part of the Pulang Lupa family.</p>
    <a href="<?php echo $plrp_disco; ?>" class="btn btn-discord" target="_blank" rel="noopener" style="margin:0 auto;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="margin-right:0.25rem;"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057c.001.022.015.04.037.05a19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
      Join Discord Now
    </a>
  </div>
</section>

<?php get_footer(); ?>
