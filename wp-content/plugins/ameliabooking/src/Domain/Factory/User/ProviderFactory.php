<?php

namespace AmeliaBooking\Domain\Factory\User;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Factory\Bookable\Service\ServiceFactory;

/**
 * Class ProviderFactory
 *
 * @package AmeliaBooking\Domain\Factory\User
 */
class ProviderFactory extends UserFactory
{
    /**
     * @param array $providers
     * @param array $services
     * @param array $providersServices
     *
     * @return Collection
     * @throws \AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException
     */
    public static function createCollection($providers, $services = [], $providersServices = [])
    {
        $providersCollection = new Collection();

        foreach ($providers as $providerKey => $providerArray) {
            $providersCollection->addItem(
                self::create($providerArray),
                $providerKey
            );

            if ($providersServices && array_key_exists($providerKey, $providersServices)) {
                foreach ((array)$providersServices[$providerKey] as $serviceKey => $providerService) {
                    if (array_key_exists($serviceKey, $services)) {
                        $providersCollection->getItem($providerKey)->getServiceList()->addItem(
                            ServiceFactory::create(array_merge(
                                $services[$serviceKey],
                                $providerService
                            )),
                            $serviceKey
                        );
                    }
                }
            }
        }

        return $providersCollection;
    }
}
