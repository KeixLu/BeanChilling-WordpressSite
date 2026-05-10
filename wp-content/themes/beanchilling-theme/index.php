<?php
/**
 * The main template file (fallback)
 */
get_header();
?>

<main>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<section class="section page-hero">
				<div class="page-wrap narrow">
					<h1><?php the_title(); ?></h1>
				</div>
			</section>
			<section class="section">
				<div class="page-wrap">
					<?php the_content(); ?>
				</div>
			</section>
		<?php endwhile; ?>
	<?php else : ?>
		<section class="section page-hero">
			<div class="page-wrap narrow">
				<h1><?php esc_html_e( 'Nothing Found', 'beanchilling' ); ?></h1>
				<p class="lede"><?php esc_html_e( 'No content was found for this page.', 'beanchilling' ); ?></p>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
