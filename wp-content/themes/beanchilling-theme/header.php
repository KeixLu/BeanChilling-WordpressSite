<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Elementor Pro: if header location is handled by Elementor, skip theme header
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
?>
<header class="site-header">
	<nav class="navbar" aria-label="<?php esc_attr_e( 'Main Navigation', 'beanchilling' ); ?>">
		<div class="nav-inner">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span class="brand-icon" aria-hidden="true"></span>
					<span class="brand-name"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>

			<?php
			if ( has_nav_menu( 'primary' ) ) :
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-links',
					'items_wrap'     => '<ul class="nav-links">%3$s</ul>',
					'walker'         => new BeanChilling_Nav_Walker(),
					'fallback_cb'    => false,
				) );
			else :
			?>
				<ul class="nav-links">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'beanchilling' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>
	</nav>
</header>
<?php endif; ?>
