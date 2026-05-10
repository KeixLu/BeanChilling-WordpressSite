<?php
/**
 * BeanChilling Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BEANCHILLING_VERSION', '1.0.0' );

/**
 * Theme Setup
 */
function beanchilling_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'beanchilling' ),
	) );

	// Elementor support
	add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'beanchilling_setup' );

/**
 * Enqueue styles and scripts
 */
function beanchilling_enqueue_assets() {
	// Google Fonts
	wp_enqueue_style(
		'beanchilling-google-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Sora:wght@400;600&display=swap',
		array(),
		null
	);

	// Theme main stylesheet
	wp_enqueue_style(
		'beanchilling-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'beanchilling-google-fonts' ),
		BEANCHILLING_VERSION
	);

	// WordPress style.css (theme metadata)
	wp_enqueue_style(
		'beanchilling-style',
		get_stylesheet_uri(),
		array( 'beanchilling-main' ),
		BEANCHILLING_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'beanchilling_enqueue_assets' );

/**
 * Register widget area (optional sidebar)
 */
function beanchilling_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'beanchilling' ),
		'id'            => 'footer-widgets',
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'beanchilling_widgets_init' );

/**
 * Add body classes for page-specific styling
 */
function beanchilling_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'page-home';
	}

	// Add page slug as body class
	if ( is_page() ) {
		global $post;
		$classes[] = 'page-' . $post->post_name;
	}

	return $classes;
}
add_filter( 'body_class', 'beanchilling_body_classes' );

/**
 * Elementor: Register locations for theme builder (Pro feature)
 */
function beanchilling_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'beanchilling_register_elementor_locations' );

/**
 * [bc_section id="X"] — renders Elementor content from any page by ID.
 * Works with free Elementor (no Pro required).
 */
function beanchilling_section_shortcode( $atts ) {
	$atts = shortcode_atts( [ 'id' => 0 ], $atts );
	$id   = absint( $atts['id'] );
	if ( ! $id || ! class_exists( '\Elementor\Plugin' ) ) {
		return '';
	}
	return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
}
add_shortcode( 'bc_section', 'beanchilling_section_shortcode' );

/**
 * Elementor: Set default content width
 */
function beanchilling_elementor_content_width() {
	return 1100;
}
add_filter( 'elementor/content_width', 'beanchilling_elementor_content_width' );

/**
 * Allow full-width Elementor pages
 */
function beanchilling_page_templates( $templates ) {
	$templates['elementor-fullwidth.php'] = esc_html__( 'Elementor Full Width', 'beanchilling' );
	$templates['elementor-canvas.php']    = esc_html__( 'Elementor Canvas (No Header/Footer)', 'beanchilling' );
	return $templates;
}
add_filter( 'theme_page_templates', 'beanchilling_page_templates' );

/**
 * Custom Walker for navigation to match original markup
 */
class BeanChilling_Nav_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$is_active = in_array( 'current-menu-item', $classes, true );

		$output .= '<li>';
		$output .= '<a href="' . esc_url( $item->url ) . '"';

		if ( $is_active ) {
			$output .= ' aria-current="page"';
		}

		$output .= '>' . esc_html( $item->title ) . '</a>';
	}
}

// AI Chatbot
require_once get_template_directory() . '/inc/chatbot.php';
