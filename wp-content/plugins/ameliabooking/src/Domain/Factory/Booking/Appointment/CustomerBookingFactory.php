<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Domain\Factory\Booking\Appointment;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Bookable\Service\PackageCustomerService;
use AmeliaBooking\Domain\Entity\Booking\Appointment\CustomerBooking;
use AmeliaBooking\Domain\Factory\Bookable\Service\PackageCustomerServiceFactory;
use AmeliaBooking\Domain\Factory\Coupon\CouponFactory;
use AmeliaBooking\Domain\Factory\Payment\PaymentFactory;
use AmeliaBooking\Domain\Factory\User\UserFactory;
use AmeliaBooking\Domain\ValueObjects\BooleanValueObject;
use AmeliaBooking\Domain\ValueObjects\Json;
use AmeliaBooking\Domain\ValueObjects\Number\Float\Price;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\IntegerValue;
use AmeliaBooking\Domain\ValueObjects\String\Token;

/**
 * Class CustomerBookingFactory
 *
 * @package AmeliaBooking\Domain\Factory\Booking\Appointment
 */
class CustomerBookingFactory
{

    /**
     * @param $data
     *
     * @return CustomerBooking
     * @throws InvalidArgumentException
     */
    public static function create($data)
    {
        $customerBooking = new CustomerBooking(
            new Id($data['customerId']),
            new BookingStatus($data['status']),
            new IntegerValue($data['persons'])
        );

        if (isset($data['id'])) {
            $customerBooking->setId(new Id($data['id']));
        }

        if (isset($data['price'])) {
            $customerBooking->setPrice(new Price($data['price']));
        }

        if (isset($data['appointmentId'])) {
            $customerBooking->setAppointmentId(new Id($data['appointmentId']));
        }

        if (isset($data['couponId'])) {
            $customerBooking->setCouponId(new Id($data['couponId']));
        }

        if (isset($data['coupon'])) {
            $customerBooking->setCoupon(CouponFactory::create($data['coupon']));
        }

        if (isset($data['customer'])) {
            $customerBooking->setCustomer(UserFactory::create($data['customer']));
        }

        if (isset($data['customFields'])) {
            $customerBooking->setCustomFields(new Json($data['customFields']));
        }

        if (isset($data['info'])) {
            $customerBooking->setInfo(new Json($data['info']));
        }

        if (isset($data['utcOffset'])) {
            $customerBooking->setUtcOffset(new IntegerValue($data['utcOffset']));
        }

        if (isset($data['aggregatedPrice'])) {
            $customerBooking->setAggregatedPrice(new BooleanValueObject($data['aggregatedPrice']));
        }

        if (isset($data['isChangedStatus'])) {
            $customerBooking->setChangedStatus(new BooleanValueObject($data['isChangedStatus']));
        }

        if (isset($data['deposit'])) {
            $customerBooking->setDeposit(new BooleanValueObject($data['deposit']));
        }

        if (isset($data['packageCustomerService'])) {
            /** @var PackageCustomerService $packageCustomerService */
            $packageCustomerService = PackageCustomerServiceFactory::create($data['packageCustomerService']);

            $customerBooking->setPackageCustomerService($packageCustomerService);
        }

        $payments = new Collection();

        if (isset($data['payments'])) {
            foreach ((array)$data['payments'] as $key => $value) {
                $payments->addItem(
                    PaymentFactory::create($value),
                    $key
                );
            }
        }

        $customerBooking->setPayments($payments);

        $extras = new Collection();

        if (isset($data['extras'])) {
            foreach ((array)$data['extras'] as $key => $value) {
                $extras->addItem(
                    CustomerBookingExtraFactory::create($value),
                    $key
                );
            }
        }

        $customerBooking->setExtras($extras);

        if (isset($data['token'])) {
            $customerBooking->setToken(new Token($data['token']));
        }

        return $customerBooking;
    }
}
