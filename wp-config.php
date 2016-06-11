<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'portezueloweb');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '}[1O&5=U~|~0/A8;8e1y9x~{hJEQ-C{.^)w8sJFtVru?&LwI|m&SzC8~X6S7:ER:');
define('SECURE_AUTH_KEY',  'f~nY2ft[Ac;xA{V`8wg?2ut~P1sd{%,#YFc0oK|J-PxRuXwbts.q:Eu.V,5tx?BT');
define('LOGGED_IN_KEY',    'IQ2qpo90CN&:ENtJtmsQRb*HWOnvGEv=%>}1uqgj2yK8@VszEue@Sc02Ok60~XLf');
define('NONCE_KEY',        'rbJvb;>(iW.{xe!ZX`_A@H{yvb?1(OEgyPf&Io~-KRml*8zYEC(]!k![c[G?`U-C');
define('AUTH_SALT',        '.fX!S<!LuR;b0RfhTU-FlsKI2O-xCNd_A88g@mItxOm+hbvKe(1ZC,! d9Xvv~.3');
define('SECURE_AUTH_SALT', 'C|&b:H/BywvS2=(z058Drv#AD-mPYCz&nQImJ/LeWX`,;BKJml%)(+0hD:<mjj4{');
define('LOGGED_IN_SALT',   'i~{{>%gR2+!%t<1PYm`*1FD9b@ @l$4^>L>f8U1(!7MS;#l?Y^l@bT@ &uz/tO<;');
define('NONCE_SALT',       '<CvU;DmQ5AWt*O!Y,C3ad;ZRi5nvpGrghs^ovZ2bp:Gap2*.G%nlaa*RGs9OAJ!l');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
