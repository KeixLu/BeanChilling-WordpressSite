<?php
/**
 * Template for the front page (Home)
 * When Elementor is used to edit, the_content() renders Elementor output.
 * Otherwise, it displays the default hero and sections.
 */
get_header();
?>

<main id="content" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( \Elementor\Plugin::$instance->db->is_built_with_elementor( get_the_ID() ) ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<!-- Default Home Content (shown until you edit with Elementor) -->
			<section class="hero section">
				<div class="page-wrap hero-grid">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'GIS Coffee Profiling Platform', 'beanchilling' ); ?></p>
						<h1><?php the_title(); ?></h1>
						<p class="lede">
							<?php esc_html_e( 'BeanChilling is an improved GIS-based application currently in development. The platform combines farm mapping and coffee profiling so coffee producers can make better field-level decisions.', 'beanchilling' ); ?>
						</p>
						<div class="btn-row">
							<a class="btn" href="<?php echo esc_url( home_url( '/app-showcase/' ) ); ?>"><?php esc_html_e( 'Explore App Features', 'beanchilling' ); ?></a>
							<a class="btn btn-secondary" href="<?php echo esc_url( home_url( '/imrad/' ) ); ?>"><?php esc_html_e( 'Read IMRAD Summary', 'beanchilling' ); ?></a>
						</div>
					</div>
					<figure class="hero-visual">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
						<?php else : ?>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/coffee_farm_main_bg.png' ); ?>" alt="<?php esc_attr_e( 'Coffee farm landscape', 'beanchilling' ); ?>">
						<?php endif; ?>
					</figure>
				</div>
			</section>

			<section class="section" id="application">
				<div class="page-wrap">
					<p class="eyebrow"><?php esc_html_e( 'Application Purpose', 'beanchilling' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'What BeanChilling is built to solve', 'beanchilling' ); ?></h2>
					<div class="card-grid three-up">
						<article class="card">
							<h3><?php esc_html_e( 'Disconnected Farm Data', 'beanchilling' ); ?></h3>
							<p><?php esc_html_e( 'Farm boundaries, crop details, and quality notes are often separated across notebooks and different tools.', 'beanchilling' ); ?></p>
						</article>
						<article class="card">
							<h3><?php esc_html_e( 'Limited Geographic Insight', 'beanchilling' ); ?></h3>
							<p><?php esc_html_e( 'Without GIS context, farmers and researchers struggle to analyze how location and terrain affect coffee characteristics.', 'beanchilling' ); ?></p>
						</article>
						<article class="card">
							<h3><?php esc_html_e( 'Hard-to-Track Profiles', 'beanchilling' ); ?></h3>
							<p><?php esc_html_e( 'BeanChilling structures coffee profiles in one place so farm records are easier to update, compare, and review over time.', 'beanchilling' ); ?></p>
						</article>
					</div>
				</div>
			</section>

			<section class="section alt">
				<div class="page-wrap split">
					<div>
						<p class="eyebrow"><?php esc_html_e( 'Research Team', 'beanchilling' ); ?></p>
						<h2 class="section-title"><?php esc_html_e( 'Meet Zenith', 'beanchilling' ); ?></h2>
						<p class="lede">
							<?php esc_html_e( 'Zenith is the research and development team behind BeanChilling. The team blends GIS, agricultural research, and software engineering to support more informed coffee production.', 'beanchilling' ); ?>
						</p>
						<a class="text-link" href="<?php echo esc_url( home_url( '/team-zenith/' ) ); ?>"><?php esc_html_e( 'View researchers and developers', 'beanchilling' ); ?></a>
					</div>
					<div class="team-mini-grid">
						<div class="mini-card"><?php esc_html_e( 'GIS Research', 'beanchilling' ); ?></div>
						<div class="mini-card"><?php esc_html_e( 'Field Validation', 'beanchilling' ); ?></div>
						<div class="mini-card"><?php esc_html_e( 'Data Profiling', 'beanchilling' ); ?></div>
						<div class="mini-card"><?php esc_html_e( 'App Engineering', 'beanchilling' ); ?></div>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php endwhile; ?>
</main>

<?php
get_footer();
