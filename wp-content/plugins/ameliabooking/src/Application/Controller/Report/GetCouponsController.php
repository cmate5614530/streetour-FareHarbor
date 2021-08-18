<?php

namespace AmeliaBooking\Application\Controller\Report;

use AmeliaBooking\Application\Commands\Report\GetCouponsCommand;
use AmeliaBooking\Application\Controller\Controller;
use Slim\Http\Request;

/**
 * Class GetCouponsController
 *
 * @package AmeliaBooking\Application\Controller\Report
 */
class GetCouponsController extends Controller
{
    /**
     * Instantiates the Get Report Customers command to hand it over to the Command Handler
     *
     * @param Request $request
     * @param         $args
     *
     * @return GetCouponsCommand
     * @throws \RuntimeException
     */
    protected function instantiateCommand(Request $request, $args)
    {
        $command = new GetCouponsCommand($args);
        $command->setField('params', (array)$request->getParams());
        $requestBody = $request->getParsedBody();
        $this->setCommandFields($command, $requestBody);

        return $command;
    }
}
