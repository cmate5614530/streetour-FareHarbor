<?php

namespace AmeliaBooking\Application\Commands\User\Provider;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Application\Services\User\ProviderApplicationService;
use AmeliaBooking\Application\Services\User\UserApplicationService;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Entity\User\Provider;
use AmeliaBooking\Domain\Factory\User\UserFactory;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\ValueObjects\String\Password;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Repository\User\ProviderRepository;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class AddProviderCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\User\Provider
 */
class AddProviderCommandHandler extends CommandHandler
{
    public $mandatoryFields = [
        'type',
        'firstName',
        'lastName',
        'email'
    ];

    /**
     * @param AddProviderCommand $command
     *
     * @return CommandResult
     * @throws ContainerValueNotFoundException
     * @throws AccessDeniedException
     * @throws InvalidArgumentException
     * @throws QueryExecutionException
     * @throws ContainerException
     */
    public function handle(AddProviderCommand $command)
    {
        if (!$this->getContainer()->getPermissionsService()->currentUserCanWrite(Entities::EMPLOYEES) ||
            !$this->getContainer()->getPermissionsService()->currentUserCanWriteOthers(Entities::EMPLOYEES)) {
            throw new AccessDeniedException('You are not allowed to add employee.');
        }

        /** @var UserApplicationService $userAS */
        $userAS = $this->getContainer()->get('application.user.service');
        /** @var ProviderApplicationService $providerAS */
        $providerAS = $this->container->get('application.user.provider.service');
        /** @var ProviderRepository $providerRepository */
        $providerRepository = $this->container->get('domain.users.providers.repository');

        $result = new CommandResult();

        $this->checkMandatoryFields($command);

        /** @var Provider $user */
        $user = UserFactory::create($command->getFields());

        if (!($user instanceof AbstractUser)) {
            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setMessage('Could not create a new user entity.');

            return $result;
        }

        $providerRepository->beginTransaction();

        if ($providerRepository->getByEmail($user->getEmail()->getValue())) {
            $result->setResult(CommandResult::RESULT_CONFLICT);
            $result->setMessage('Email already exist.');
            $result->setData('This email already exists.');

            return $result;
        }

        try {
            $userId = $providerAS->add($user);

            if ($command->getField('externalId') === 0) {
                $userAS->setWpUserIdForNewUser($userId, $user);
            }

            if ($command->getField('password')) {
                $newPassword = new Password($command->getField('password'));

                $providerRepository->updateFieldById($userId, $newPassword->getValue(), 'password');
            }

            $user->setId(new Id($userId));
        } catch (QueryExecutionException $e) {
            $providerRepository->rollback();
            throw $e;
        }

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully added new user.');
        $result->setData(
            [
                Entities::USER                 => $user->toArray(),
                'sendEmployeePanelAccessEmail' =>
                    $command->getField('password') && $command->getField('sendEmployeePanelAccessEmail'),
                'password'                     => $command->getField('password')
            ]
        );

        $providerRepository->commit();

        return $result;
    }
}
