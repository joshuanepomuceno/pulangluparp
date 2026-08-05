<?php get_header(); ?>

<main style="padding-top:70px; min-height:80vh;">
  <div class="page-hero">
    <h1><?php the_title(); ?></h1>
    <p class="breadcrumb"><a href="<?php echo home_url(); ?>">Home</a> &rsaquo; <?php the_title(); ?></p>
  </div>

  <div class="section-inner" style="padding:3rem 1.5rem; max-width:860px;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="entry-content" style="color:var(--text-muted); line-height:1.8;">
        <?php the_content(); ?>
      </div>
    <?php endwhile; endif; ?>
  </div>
</main>

<?php get_footer(); ?>
