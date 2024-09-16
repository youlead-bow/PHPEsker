<?php

namespace Esker\Common;

use SoapClient;

/**
 * Class Debug
 * @package Esker\Common
 */
class Debug extends SoapClient
{
    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int $version
     * @param int $oneWay
     * @return string
     */
    public function __doRequest(string $request, string $location, string $action, int $version, int|bool $oneWay = 0): string
    {
        self::dump($request);
        return parent::__doRequest($request, $location, $action, $version, $oneWay);
    }

    /**
     * @param mixed ...$vars
     * @return void
     */
    public static function dump(...$vars): void
    {
        $output = '<pre>';
        foreach ($vars as $var) {
            $output .= print_r($var, true);
        }
        $output .= '</pre>';
        echo $output;
    }
}