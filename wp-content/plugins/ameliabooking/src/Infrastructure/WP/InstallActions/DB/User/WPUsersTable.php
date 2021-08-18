<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\InstallActions\DB\User;

use AmeliaBooking\Infrastructure\WP\InstallActions\DB\AbstractDatabaseTable;

/**
 * Class WPUsersTable
 *
 * @package AmeliaBooking\Infrastructure\WP\InstallActions\DB\User
 */
class WPUsersTable extends AbstractDatabaseTable
{
    const TABLE = 'users';

    const META_TABLE = 'usermeta';

    /**
     * @return string
     */
    public static function getTableName()
    {
        return self::getDatabaseBasePrefix() . static::TABLE;
    }

    /**
     * @return string
     */
    public static function getMetaTableName()
    {
        return self::getDatabaseBasePrefix() . static::META_TABLE;
    }

    /**
     * @return string
     */
    public static function getDatabasePrefix()
    {
        global $wpdb;
        return $wpdb->prefix;
    }

    /**
     * @return string
     */
    public static function getDatabaseBasePrefix()
    {
        global $wpdb;
        return $wpdb->base_prefix;
    }
}
