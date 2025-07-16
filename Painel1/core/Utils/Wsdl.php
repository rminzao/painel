<?php

namespace Core\Utils;

use Core\Utils\Game\ReloadType;
use SoapClient;

class Wsdl extends ReloadType
{
    /** @var string */
    public ?string $domain;

    /** @var string */
    public string $method;

    /** @var array */
    public array $paramters;

    public function __construct(string $domain = null)
    {
        $this->domain = $domain;
    }

    public function error(string $message)
    {
        return [
            'status' => 'error',
            'message' => $message,
        ];
    }

    public function reload($type, $server, &$message = null)
    {
        if ($server->wsdl == '') {
            $message?->warning("O servidor informado não possui um dominio de wsdl.");
            return false;
        }

        $settings = json_decode(unserialize($server->settings));

        $parameters = [
            'type' => $type,
        ];

        if (!in_array($settings->areaid, [null, ''])) {
            $parameters = array_merge($parameters, [
                'zoneId' => $settings->areaid
            ]);
        }

        $this->domain = $server->wsdl;
        $this->method = 'Reload';
        $this->paramters = $parameters;

        return $this->send();
    }

    public function send()
    {
        $method = $this->method;

        try {
            ini_set('default_socket_timeout', 3);
            $soap = new SoapClient('http://' . $this->domain . '/CenterService/?wsdl');
            return $soap->$method($this->paramters ?? []);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
