<?php

namespace AmeliaBooking\Application\Commands;

use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\WP\SettingsService\SettingsStorage;
use Slim\Http\Request;

/**
 * Class Command
 *
 * @package AmeliaBooking\Application\Commands
 */
abstract class Command
{
    protected $args;

    protected $container;

    private $fields = [];

    public $token;

    private $page;

    private $cabinetType;

    /**
     * Command constructor.
     *
     * @param $args
     */
    public function __construct($args)
    {
        $this->args = $args;
        if (isset($args['type'])) {
            $this->setField('type', $args['type']);
        }
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param mixed $arg Argument to be fetched
     *
     * @return null
     */
    public function getArg($arg)
    {
        return isset($this->args[$arg]) ? $this->args[$arg] : null;
    }

    /**
     * @param $fieldName
     * @param $fieldValue
     */
    public function setField($fieldName, $fieldValue)
    {
        $this->fields[$fieldName] = $fieldValue;
    }

    /**
     * @param $fieldName
     */
    public function removeField($fieldName)
    {
        unset($this->fields[$fieldName]);
    }

    /**
     * Return a single field
     *
     * @param $fieldName
     *
     * @return mixed|null
     */
    public function getField($fieldName)
    {
        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : null;
    }

    /**
     * Return all fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set Token
     *
     * @param Request $request
     */
    public function setToken($request)
    {
        $headers = $request->getHeaders();

        $token = null;

        /** @var SettingsService $settingsService */
        $settingsService = new SettingsService(new SettingsStorage());

        if (isset($headers['HTTP_AUTHORIZATION'][0]) &&
            ($values = explode(' ', $request->getHeaders()['HTTP_AUTHORIZATION'][0])) &&
            sizeof($values) === 2 &&
            $settingsService->getSetting('roles', 'enabledHttpAuthorization')
        ) {
            $token = $values[1];
        } else if (isset($headers['HTTP_COOKIE'][0])) {
            foreach (explode('; ', $headers['HTTP_COOKIE'][0]) as $cookie) {
                if (($ameliaTokenCookie = explode('=', $cookie)) && $ameliaTokenCookie[0] === 'ameliaToken') {
                    $token = $ameliaTokenCookie[1];
                }
            }
        }

        $this->token = $token;
    }

    /**
     * Return Token
     *
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set page
     *
     * @param string $page
     */
    public function setPage($page)
    {
        $this->page = explode('-', $page)[0];
        $this->cabinetType = explode('-', $page)[1];
    }

    /**
     * Return page
     *
     * @return string|null
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Return cabinet type
     *
     * @return string|null
     */
    public function getCabinetType()
    {
        return $this->cabinetType;
    }
}
