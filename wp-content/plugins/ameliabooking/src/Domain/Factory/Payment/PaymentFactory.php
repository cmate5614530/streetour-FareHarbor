<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Domain\Factory\Payment;

use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Payment\PaymentGateway;
use AmeliaBooking\Domain\Entity\Payment\Payment;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\ValueObjects\DateTime\DateTimeValue;
use AmeliaBooking\Domain\ValueObjects\Number\Float\Price;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\ValueObjects\String\PaymentStatus;
use AmeliaBooking\Domain\ValueObjects\String\Name;
use AmeliaBooking\Domain\ValueObjects\String\PaymentData;

/**
 * Class PaymentFactory
 *
 * @package AmeliaBooking\Domain\Factory\Payment
 */
class PaymentFactory
{
    /**
     * @param $data
     *
     * @return Payment
     * @throws InvalidArgumentException
     */
    public static function create($data)
    {
        if (isset($data['data']) && !is_string($data['data'])) {
            $data['data'] = json_encode($data['data'], true);
        }

        $payment = new Payment(
            new Id($data['customerBookingId']),
            new Price($data['amount']),
            new DateTimeValue(DateTimeService::getCustomDateTimeObject($data['dateTime'])),
            new PaymentStatus($data['status']),
            new PaymentGateway(new Name($data['gateway'])),
            new PaymentData(isset($data['data']) ? $data['data'] : '')
        );

        if (isset($data['id'])) {
            $payment->setId(new Id($data['id']));
        }

        if (!empty($data['gatewayTitle'])) {
            $payment->setGatewayTitle(new Name($data['gatewayTitle']));
        }

        if (!empty($data['packageCustomerId'])) {
            $payment->setPackageCustomerId(new Id($data['packageCustomerId']));
        }

        if (!empty($data['parentId'])) {
            $payment->setParentId(new Id($data['parentId']));
        }

        return $payment;
    }
}
