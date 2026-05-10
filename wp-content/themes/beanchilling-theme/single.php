<?php
/**
 * Single post template
 */
get_header();
?>

<main class="site-main">
	<?php while ( have_posts() ) : the_post();
		$is_elementor = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( get_the_ID() );
	?>
		<?php if ( ! $is_elementor ) : ?>
		<section class="section page-hero">
			<div class="page-wrap narrow">
				<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section class="section">
			<div class="page-wrap narrow">
				<?php the_content(); ?>
			</div>
		</section>
		<?php else : ?>
		<?php the_content(); ?>
		<?php endif; ?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
