<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\InstallActions\DB\Payment;

use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Infrastructure\WP\InstallActions\DB\AbstractDatabaseTable;

/**
 * Class PaymentsTable
 *
 * @package AmeliaBooking\Infrastructure\WP\InstallActions\DB\Payment
 */
class PaymentsTable extends AbstractDatabaseTable
{

    const TABLE = 'payments';

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public static function buildTable()
    {
        $table = self::getTableName();

        return "CREATE TABLE {$table} (
                   `id` int(11) NOT NULL AUTO_INCREMENT,
                   `customerBookingId` int(11) NULL,
                   `amount` DOUBLE NOT NULL default 0,
                   `dateTime` datetime NULL,
                   `status` ENUM('paid', 'pending', 'partiallyPaid') NOT NULL,
                   `gateway` ENUM('onSite', 'payPal', 'stripe', 'wc', 'mollie') NOT NULL,
                   `gatewayTitle` varchar(255) NULL,
                   `data` text NULL,
                   `packageCustomerId` int(11) NULL,
                   `parentId` int(11) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    public static function alterTable()
    {
        $table = self::getTableName();

        return ["ALTER TABLE {$table} MODIFY customerBookingId INT(11) NULL"];
    }
}
