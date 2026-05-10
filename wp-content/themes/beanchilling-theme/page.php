<?php
/**
 * Template for individual pages - Elementor editable
 */
get_header();
?>

<main id="content" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
