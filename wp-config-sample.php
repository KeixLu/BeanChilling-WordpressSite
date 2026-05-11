<?php
/**
 * WordPress Configuration Sample — BeanChilling
 *
 * Copy this file to wp-config.php and fill in the values.
 * NEVER commit wp-config.php to the repository.
 */

// --- Database settings ---
define( 'DB_NAME',     'beanchilling_wp' );
define( 'DB_USER',     'your-db-user' );
define( 'DB_PASSWORD', 'your-db-password' );
define( 'DB_HOST',     'localhost' );
define( 'DB_CHARSET',  'utf8mb4' );
define( 'DB_COLLATE',  '' );

// --- AI Chatbot (Vertex AI Express) ---
// Get your AQ. prefix key from: https://aistudio.google.com/apikey
// Enable vertexai=True when generating the key (Python SDK) or use x-goog-api-key header
define( 'GEMINI_API_KEY', 'AQ.your-vertex-ai-express-key-here' );

// --- Dynamic URL detection (works locally and on GCP VM) ---
$_bc_host   = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'localhost';
$_bc_scheme = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) ? 'https' : 'http';
define( 'WP_HOME',    $_bc_scheme . '://' . $_bc_host . '/beanchilling' );
define( 'WP_SITEURL', $_bc_scheme . '://' . $_bc_host . '/beanchilling' );
unset( $_bc_host, $_bc_scheme );

// --- Debug (disable on production) ---
define( 'WP_DEBUG',         false );
define( 'WP_DEBUG_LOG',     false );
define( 'WP_DEBUG_DISPLAY', false );

// --- Authentication keys (generate at: https://api.wordpress.org/secret-key/1.1/salt/) ---
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

$table_prefix = 'wp_';

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
