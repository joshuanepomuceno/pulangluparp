<?php get_header(); ?>

<main style="padding-top:70px; min-height:80vh;">
  <div class="page-hero">
    <h1><?php
      if (is_home()) { echo 'Latest Posts'; }
      elseif (is_archive()) { the_archive_title(); }
      elseif (is_search()) { printf('Search Results for: %s', get_search_query()); }
      else { the_title(); }
    ?></h1>
  </div>

  <div class="section-inner" style="padding:3rem 1.5rem;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom:2rem; border-bottom:1px solid var(--border); padding-bottom:2rem;">
        <h2 style="font-size:1.4rem; margin-bottom:0.5rem;">
          <a href="<?php the_permalink(); ?>" style="color:var(--gold);"><?php the_title(); ?></a>
        </h2>
        <div style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem;">
          <?php echo get_the_date(); ?> &bull; <?php the_author(); ?>
        </div>
        <div style="color:var(--text-muted);"><?php the_excerpt(); ?></div>
        <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="margin-top:1rem; font-size:0.75rem; padding:0.5rem 1.25rem;">Read More</a>
      </article>
    <?php endwhile; else : ?>
      <p style="color:var(--text-muted); text-align:center; padding:3rem 0;">No content found.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
