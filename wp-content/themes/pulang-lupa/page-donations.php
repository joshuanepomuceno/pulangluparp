<?php
/**
 * Template Name: Donations Page
 *
 * Data-driven Donation Terms & Conditions for Pulang Lupa Roleplay.
 * Shares the handbook UI (classes / JS) with page-rules.php and
 * page-factions.php. The full document lives in the $don_intro /
 * $don_chapters arrays below and is rendered into both the sidebar
 * table of contents and the main content.
 *
 * Block types (see plrp_donation_block):
 *   ['p', 'text']  ['lead', 'text']  ['h', 'sub-heading']
 *   ['note', 'call-out text']
 *   ['list', 'Intro:', 'must|mustnot|neutral', [items…]]
 */
get_header();

$don_intro = [
    'lead' => "Pulang Lupa RP is free to play. Donations are completely voluntary and help support server hosting, development, community events, and future content. As a token of appreciation, supporters may receive cosmetic or roleplay-focused rewards that do not compromise the fairness of the server.",
];

$don_chapters = [
    [
        'id' => 'chapter-1', 'numeral' => 'I', 'label' => 'Chapter 1',
        'title' => 'Donation Terms & Conditions',
        'desc'  => 'What your support means, what it buys, and what it never will.',
        'rules' => [
            [
                'id' => 'acceptance-of-terms', 'num' => 1, 'title' => 'Acceptance of Terms',
                'blocks' => [
                    ['p', "By making a voluntary donation to Pulang Lupa RP, you acknowledge that you have read, understood, and agreed to these Terms & Conditions, the Server Rules, the Supporter Guide, and any other applicable policies published by Server Management."],
                ],
            ],
            [
                'id' => 'nature-of-donations', 'num' => 2, 'title' => 'Nature of Donations',
                'blocks' => [
                    ['p', "All donations are voluntary contributions intended to support the operation, maintenance, development, and continued growth of Pulang Lupa RP."],
                    ['p', "Donations are not purchases of goods or services, nor do they grant ownership, investment, partnership, or any financial interest in Pulang Lupa RP."],
                    ['note', "Supporter rewards are provided solely as a gesture of appreciation for your contribution."],
                ],
            ],
            [
                'id' => 'supporter-rewards', 'num' => 3, 'title' => 'Supporter Rewards',
                'blocks' => [
                    ['p', "Supporter rewards are intended to enhance roleplay while preserving fairness for the entire community."],
                    ['list', "Supporter rewards:", 'neutral', [
                        "Are appreciation-based benefits and not purchased products.",
                        "Do not grant administrative authority or special privileges.",
                        "May be adjusted, replaced, suspended, or removed when necessary to maintain server balance or operational integrity.",
                    ]],
                    ['note', "Server Management reserves the sole discretion to determine the availability and eligibility of all supporter rewards."],
                ],
            ],
            [
                'id' => 'fair-play', 'num' => 4, 'title' => 'Fair Play',
                'blocks' => [
                    ['p', "Pulang Lupa RP is committed to maintaining a fair and story-driven roleplay environment."],
                    ['list', "Donations shall never provide:", 'mustnot', [
                        "Pay-to-win advantages.",
                        "Exemptions from Server Rules.",
                        "Immunity from administrative action.",
                        "Preferential treatment from staff.",
                        "Guaranteed roleplay outcomes or positions.",
                    ]],
                    ['note', "Every player is expected to earn their reputation, influence, businesses, and accomplishments through meaningful roleplay, regardless of supporter status."],
                ],
            ],
            [
                'id' => 'refund-policy', 'num' => 5, 'title' => 'Refund Policy',
                'blocks' => [
                    ['p', "Unless otherwise required by applicable law, all donations are final and non-refundable."],
                    ['p', "Before making a donation, supporters are encouraged to carefully review the available rewards and ensure they understand the applicable policies."],
                ],
            ],
            [
                'id' => 'chargebacks-disputes', 'num' => 6, 'title' => 'Chargebacks & Payment Disputes',
                'blocks' => [
                    ['p', "If you experience an issue with your donation, please contact Server Management before initiating a chargeback or payment dispute."],
                    ['list', "Unauthorized chargebacks or fraudulent payment disputes may result in:", 'mustnot', [
                        "Removal of supporter rewards.",
                        "Suspension of your account.",
                        "Permanent removal from the community.",
                        "Restriction from future supporter benefits.",
                    ]],
                    ['p', "This policy exists to protect Pulang Lupa RP from fraudulent transactions and payment abuse."],
                ],
            ],
            [
                'id' => 'trading-transfers', 'num' => 7, 'title' => 'Trading & Transfers',
                'blocks' => [
                    ['p', "Supporter rewards are intended solely for the account that received them."],
                    ['list', "Unless expressly approved by Server Management, supporter rewards may not be:", 'mustnot', [
                        "Sold.",
                        "Traded.",
                        "Transferred.",
                        "Exchanged.",
                        "Auctioned.",
                        "Converted into real-world currency or anything of equivalent value.",
                    ]],
                    ['note', "Any attempt to circumvent this policy may result in the removal of the rewards and additional administrative action."],
                ],
            ],
            [
                'id' => 'rule-violations', 'num' => 8, 'title' => 'Rule Violations',
                'blocks' => [
                    ['p', "Supporters remain subject to all Server Rules and community policies."],
                    ['p', "Violation of server rules may result in disciplinary action, including the suspension or permanent removal of supporter rewards, without refund."],
                    ['note', "Supporter status shall never exempt a player from moderation or enforcement."],
                ],
            ],
            [
                'id' => 'changes-to-benefits', 'num' => 9, 'title' => 'Changes to Supporter Benefits',
                'blocks' => [
                    ['p', "Pulang Lupa RP is an evolving community project."],
                    ['list', "To maintain balance, fairness, and long-term sustainability, Server Management reserves the right to modify:", 'neutral', [
                        "Supporter rewards.",
                        "Donation tiers.",
                        "Pricing.",
                        "Eligibility requirements.",
                        "These Terms & Conditions.",
                    ]],
                    ['note', "Whenever reasonably possible, significant changes will be communicated to the community in advance."],
                ],
            ],
            [
                'id' => 'limitation-of-liability', 'num' => 10, 'title' => 'Limitation of Liability',
                'blocks' => [
                    ['p', "Pulang Lupa RP is a community-operated roleplay server maintained by volunteers. While Server Management strives to provide a stable, fair, and enjoyable experience, uninterrupted service cannot be guaranteed."],
                    ['list', "By making a donation, you acknowledge and agree that Pulang Lupa RP, its owners, management, administrators, and staff shall not be held liable for:", 'neutral', [
                        "Server downtime, outages, or scheduled maintenance.",
                        "Character loss, data loss, or technical issues.",
                        "Gameplay updates, balance changes, or feature removals.",
                        "Interruptions or discontinuation of server services.",
                        "Modification, suspension, replacement, or removal of supporter rewards necessary to maintain the server.",
                    ]],
                    ['note', "Supporters understand that donations are made voluntarily in support of an evolving community project, and no guarantee is made regarding the permanent availability of any specific reward, feature, or service."],
                ],
            ],
            [
                'id' => 'entire-agreement', 'num' => 11, 'title' => 'Entire Agreement',
                'blocks' => [
                    ['p', "These Terms & Conditions, together with the Server Rules, Supporter Guide, and any officially published server policies, constitute the complete agreement between Pulang Lupa RP and its supporters regarding donations and supporter rewards."],
                    ['p', "Any exceptions, modifications, or special arrangements shall only be valid if expressly approved by Server Management in writing."],
                ],
            ],
            [
                'id' => 'acknowledgement', 'num' => 12, 'title' => 'Acknowledgement',
                'blocks' => [
                    ['p', "By making a voluntary donation to Pulang Lupa RP, you acknowledge that you have read, understood, and agreed to these Terms & Conditions."],
                    ['p', "Your contribution directly supports the continued development of the server and helps us create a fair, immersive, and enjoyable roleplay experience for the entire community. While supporter rewards are provided as a token of appreciation, the true value of your support lies in helping Pulang Lupa RP continue to grow for everyone."],
                ],
            ],
        ],
    ],
];

/* -------------------------------------------------------------------------
   BLOCK RENDERER (donations — shares the p/lead/h/note/list block set)
------------------------------------------------------------------------- */
function plrp_donation_block( $block ) {
    switch ( $block[0] ) {
        case 'lead':
            echo '<p class="rule-lead">' . esc_html( $block[1] ) . '</p>';
            break;
        case 'p':
            echo '<p>' . esc_html( $block[1] ) . '</p>';
            break;
        case 'h':
            echo '<h4 class="rule-subhead">' . esc_html( $block[1] ) . '</h4>';
            break;
        case 'note':
            echo '<div class="rule-note"><span class="rule-note-icon" aria-hidden="true">!</span><p>' . esc_html( $block[1] ) . '</p></div>';
            break;
        case 'list':
            echo '<div class="rule-list rule-list--' . esc_attr( $block[2] ) . '">';
            if ( $block[1] ) {
                echo '<p class="rule-list-title">' . esc_html( $block[1] ) . '</p>';
            }
            echo '<ul>';
            foreach ( $block[3] as $item ) {
                echo '<li>' . esc_html( $item ) . '</li>';
            }
            echo '</ul></div>';
            break;
    }
}
?>

<div class="reading-progress" aria-hidden="true"><span id="reading-progress-bar"></span></div>

<div class="donations-page">

  <!-- ── Page Header ─────────────────────────────────────── -->
  <header class="rules-page-header">
    <div class="rules-header-inner">
      <p class="rules-eyebrow">Server Guide</p>
      <h1>Donations</h1>
      <p class="rules-subhead">Pulang Lupa RP is free to play. Here's how voluntary support works, and the terms every supporter agrees to.</p>
      <ul class="rules-tags">
        <li>Voluntary</li>
        <li>No Pay-to-Win</li>
        <li>Final Sale</li>
        <li>18+ Community</li>
      </ul>
      <div class="rules-header-actions">
        <button type="button" class="rules-print-btn" id="rules-print">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Print / Save PDF
        </button>
      </div>
    </div>
  </header>

  <div class="rules-layout">

    <!-- ── LEFT SIDEBAR ─────────────────────────────────── -->
    <aside class="rules-sidebar">
      <div class="rules-search-wrap">
        <input type="text" id="rules-search" placeholder="Search the donation terms&hellip;" autocomplete="off" aria-label="Search donation terms">
        <svg class="rules-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
        </svg>
      </div>

      <nav class="rules-nav" id="rules-nav" aria-label="Donations table of contents">
        <?php foreach ( $don_chapters as $ci => $chapter ) : ?>
          <div class="rules-nav-chapter<?php echo $ci === 0 ? ' open' : ''; ?>" data-chapter="<?php echo esc_attr( $chapter['id'] ); ?>">
            <button type="button" class="rules-nav-chapter-toggle">
              <span class="rules-nav-chapter-label"><?php echo esc_html( $chapter['label'] ); ?></span>
              <span class="rules-nav-chapter-title"><?php echo esc_html( $chapter['title'] ); ?></span>
              <svg class="rules-nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <ul class="rules-nav-sublist">
              <?php foreach ( $chapter['rules'] as $rule ) : ?>
                <li><a href="#<?php echo esc_attr( $rule['id'] ); ?>" class="rules-nav-link"><span class="rules-nav-num"><?php echo esc_html( $rule['num'] ); ?></span><?php echo esc_html( $rule['title'] ); ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
        <p class="rules-no-results">No sections match your search.</p>
      </nav>
    </aside>

    <!-- ── MAIN CONTENT ─────────────────────────────────── -->
    <main class="rules-main">

      <!-- Intro / overview -->
      <section class="rules-intro" id="donations-intro">
        <p class="rule-lead"><?php echo esc_html( $don_intro['lead'] ); ?></p>
      </section>

      <?php foreach ( $don_chapters as $chapter ) : ?>
        <section class="rule-chapter" id="<?php echo esc_attr( $chapter['id'] ); ?>" data-chapter="<?php echo esc_attr( $chapter['id'] ); ?>">

          <div class="chapter-divider">
            <span class="chapter-numeral"><?php echo esc_html( $chapter['numeral'] ); ?></span>
            <div class="chapter-heading">
              <span class="chapter-label"><?php echo esc_html( $chapter['label'] ); ?></span>
              <h2 class="chapter-title"><?php echo esc_html( $chapter['title'] ); ?></h2>
              <p class="chapter-desc"><?php echo esc_html( $chapter['desc'] ); ?></p>
            </div>
          </div>

          <?php foreach ( $chapter['rules'] as $rule ) : ?>
            <article class="rule-section" id="<?php echo esc_attr( $rule['id'] ); ?>">
              <div class="rule-section-header">
                <div class="rule-section-title">
                  <span class="rule-number"><?php echo esc_html( $rule['num'] ); ?></span>
                  <h3><?php echo esc_html( $rule['title'] ); ?></h3>
                </div>
                <div class="rule-actions">
                  <button type="button" class="rule-copy-link" data-href="#<?php echo esc_attr( $rule['id'] ); ?>" aria-label="Copy link to this section">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    <span>Link</span>
                  </button>
                </div>
              </div>
              <div class="rule-body">
                <?php foreach ( $rule['blocks'] as $block ) { plrp_donation_block( $block ); } ?>
              </div>
            </article>
          <?php endforeach; ?>

        </section>
      <?php endforeach; ?>

    </main>

  </div><!-- .rules-layout -->
</div><!-- .donations-page -->

<button type="button" class="rules-back-to-top" id="rules-back-to-top" aria-label="Back to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<?php get_footer(); ?>
