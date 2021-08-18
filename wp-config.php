<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
//define( 'WPCACHEHOME', '/home/dh_uuun5h/streetours.com/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'streetours');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '|;:D7lTnGJrma!9^jufM2~J&sWx~n(%:w3?P`XDboW^YYt|r1|Ey;r6Vh5?h7a4o');
define('SECURE_AUTH_KEY',  '3i9sy0e|#(V#W6GUq/dZvSsDfFDno~N%BXd&PmItYc``tN?25JCO~0uG*i!3cPG7');
define('LOGGED_IN_KEY',    'GS9+i~_b$JXh/9YfJ&XePmGjOXOyg2*M?CqZXkJ:WYaJlXJH4!Kge`fcHsk0y*m2');
define('NONCE_KEY',        '9Lo+a!"sH#$3t5h*mggk98zyMDtGzsQ!Uh+ZEErv6M&(W81(Xz4%I5U2$sa_6PE/');
define('AUTH_SALT',        'f/PO&!CQtHR?d1Y)8H$kSr%OlrJRyTDMLoTV:KzjN26sRYOt)fWl;bx/re^etb#y');
define('SECURE_AUTH_SALT', 'qsu9a5DI24NjQoAa/k7F/u?^(NaZBb|mqu2#(jptONJaCq2U!G*O9EszBO3Q@AV*');
define('LOGGED_IN_SALT',   '`Hr4tu7t4sQiQh|Z;FsYw:^?vGXJ#Ho@c*PUc:Uou^M0nJt(5erdiDQ~2On&P48O');
define('NONCE_SALT',       'v|U9?M+_tlMwe&6`Xno1^Hhmc_4A8VsM0;geX+Yd:92YoP:!1T5x:;j6k&R+VkfP');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 * Removing this could cause issues with your experience in the DreamHost panel
 */

if (preg_match("/^(.*)\.dream\.website$/", $_SERVER['HTTP_HOST'])) {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        define('WP_SITEURL', $proto . '://' . $_SERVER['HTTP_HOST']);
        define('WP_HOME',    $proto . '://' . $_SERVER['HTTP_HOST']);
}


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

