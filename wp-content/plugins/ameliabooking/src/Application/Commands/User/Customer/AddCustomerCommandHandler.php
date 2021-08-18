<?php

namespace AmeliaBooking\Application\Commands\User\Customer;

use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Application\Services\User\UserApplicationService;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Factory\User\UserFactory;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Infrastructure\Repository\User\UserRepository;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;

/**
 * Class AddCustomerCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\User\Customer
 */
class AddCustomerCommandHandler extends CommandHandler
{

    public $mandatoryFields = [
        'type',
        'firstName',
        'lastName',
        'email'
    ];

    /**
     * @param AddCustomerCommand $command
     *
     * @return CommandResult
     * @throws \Slim\Exception\ContainerValueNotFoundException
     * @throws AccessDeniedException
     * @throws InvalidArgumentException
     * @throws \AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function handle(AddCustomerCommand $command)
    {
        if (!$this->getContainer()->getPermissionsService()->currentUserCanWrite(Entities::CUSTOMERS)) {
            throw new AccessDeniedException('You are not allowed to perform this action!');
        }

        $result = new CommandResult();

        $this->checkMandatoryFields($command);

        if ($command->getField('externalId') === -1) {
            $command->setField('externalId', null);
        }

        $user = UserFactory::create($command->getFields());

        if (!$user instanceof AbstractUser) {
            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setMessage('Could not create a new user entity.');

            return $result;
        }

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get('domain.users.repository');

        $userRepository->beginTransaction();

        if ($userRepository->getByEmail($user->getEmail()->getValue())) {
            $result->setResult(CommandResult::RESULT_CONFLICT);
            $result->setMessage('Email already exist.');
            $result->setData('This email already exists.');

            return $result;
        }

        if ($userId = $userRepository->add($user)) {
            $user->setId(new Id($userId));

            if ($command->getField('externalId') === 0) {
                /** @var UserApplicationService $userAS */
                $userAS = $this->getContainer()->get('application.user.service');

                $userAS->setWpUserIdForNewUser($userId, $user);
            }

            $result->setResult(CommandResult::RESULT_SUCCESS);
            $result->setMessage('Successfully added new user.');
            $result->setData([
                Entities::USER => $user->toArray()
            ]);

            $userRepository->commit();

            return $result;
        }

        $userRepository->rollback();

        return $result;
    }
}
