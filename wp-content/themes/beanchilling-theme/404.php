<?php
/**
 * 404 template
 */
get_header();
?>

<main>
	<section class="section page-hero">
		<div class="page-wrap narrow">
			<p class="eyebrow"><?php esc_html_e( '404', 'beanchilling' ); ?></p>
			<h1><?php esc_html_e( 'Page Not Found', 'beanchilling' ); ?></h1>
			<p class="lede"><?php esc_html_e( 'The page you are looking for does not exist. Try heading back to the home page.', 'beanchilling' ); ?></p>
			<div class="btn-row">
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go Home', 'beanchilling' ); ?></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
