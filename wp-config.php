<?php
define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'beanchilling_wp' );

/** Database username */
define( 'DB_USER', 'wpuser' );

/** Database password */
define( 'DB_PASSWORD', 'wpuser' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '+eT;Jz.ReF%5#a X/g)x:hA,esXXu6VE~<zXWG/VC6]}p-vg](=YioJb%0M_GIaG' );
define( 'SECURE_AUTH_KEY',  'Xgu:ne<`=~3zDiocQtuY(Ustk78Km^2{-ryk}-nr|%iNOZ(/N_?s;<0V|P;>kHG1' );
define( 'LOGGED_IN_KEY',    '!2be4,Z+|?^7{]ds#Yr5z??,L]:`{q+90n8;>s2!z`fn]tjqM;yL.&Q/!U+Mi7Sb' );
define( 'NONCE_KEY',        ']m`O!y[Eew?isY=W?[5WZA)bI)sD*a BKL?<mmMq0{)+;aT<%kRv-F=l Rh5S%v5' );
define( 'AUTH_SALT',        ';7cdK(1B*pmkspL~#Y%O>% &pvdiUSIe#du]QJr25bp#7f}ju|6Zn54ZKPK=2<HA' );
define( 'SECURE_AUTH_SALT', 'AVzhq+hFjv=fWLK4F&_vGghPjqZ`smR]7<5US?#f#b1/awkRNXsBD7`K?S3|oR8.' );
define( 'LOGGED_IN_SALT',   'KruwFeykR+){rA?)8_$RNLXW5pvIA.niV#9G[4_tn!$-TTuJGHmlOT{E.cOc,ky{' );
define( 'NONCE_SALT',       'pG=m,dZ]hFr(#<^X?EuBaH^L t@.&[*%q<Zs]LPlYS<+()l!6?aU(_>+Y-~b4PS}' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

// Groq API key for the AI chatbot (genuinely free — get yours at https://console.groq.com/keys)
define('GEMINI','AIzaSyCAspISIOQ-noZMQbvDcU95Rp2EXkmZIRA');

// Dynamic site URL — makes all image/asset URLs work from any device on the network.
$_bc_host   = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'localhost';
$_bc_scheme = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) ? 'https' : 'http';
define( 'WP_HOME',    $_bc_scheme . '://' . $_bc_host . '/beanchilling' );
define( 'WP_SITEURL', $_bc_scheme . '://' . $_bc_host . '/beanchilling' );
unset( $_bc_host, $_bc_scheme );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
