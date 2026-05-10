<?php
// Elementor Pro: if footer location is handled by Elementor, skip theme footer
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
?>
<footer class="site-footer">
	<div class="page-wrap">
		<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
			<?php dynamic_sidebar( 'footer-widgets' ); ?>
		<?php else : ?>
			<p><?php echo esc_html__( 'BeanChilling by Team Zenith.', 'beanchilling' ); ?></p>
		<?php endif; ?>
	</div>
</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
