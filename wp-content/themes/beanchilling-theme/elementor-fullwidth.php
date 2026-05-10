<?php
/**
 * Template Name: Elementor Full Width
 * Description: Full-width page template with header and footer, no sidebar. Ideal for Elementor.
 */
get_header();
?>

<main class="elementor-fullwidth-content">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
