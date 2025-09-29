<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'aifarming' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'VXHVg@+M3pE)X7?eMU?GO,4vZ@#*.5_:ncH1_gH#Ko8@Qpv2~5.19G!9kHsNUZZZ' );
define( 'SECURE_AUTH_KEY',  '/E*+9Xh3n0*-U@^x-<j=r71Xrt6PB6eZ?,7rS+u=F9UPZiK3K2jxHy}w|v)]LerX' );
define( 'LOGGED_IN_KEY',    'LE|{xZ1|XkhHt<7J:,a0lwzSU2,6TzQl^s-=e5Vo^mt8iT]BVt%]IM?0FYl.geW9' );
define( 'NONCE_KEY',        '?Z}0,k0h-SjzXC`Boy29ssXxd{|Jjq-]<]e$Uz=@Oq~esRUos96<&8Ynf)jF(`/w' );
define( 'AUTH_SALT',        '3v*reR|j6LQ~?ly9fTk0_C70}dC[tcyI:E+`n0uaP|i%cV7I>3=^5.i3+|*N0;p8' );
define( 'SECURE_AUTH_SALT', 'CS{lB7:BX.|NFr_0E3e=?=wBqF1{CGCFbL!atb7KL}of* ,aa[ShazAs#qLPN4rC' );
define( 'LOGGED_IN_SALT',   'D[x,r] .#]`^_K`uPC!:mX V{{;3gNw,AK|!@1J]dO(5SH6wa800,w/mY9[d!?/-' );
define( 'NONCE_SALT',       '-TC2)V,y2tbUq((?/`nsBgT}(;~SuC@vwzo8+ttOkd&3[q9V@%4DU1(AGPRqO7Y6' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'aifrm_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', false );
// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
