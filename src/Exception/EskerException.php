<?php

namespace Esker\Exception;

use Exception;

/**
 * Class SoapException
 * @package Esker\Exception
 */
class EskerException extends Exception
{
    /**
     * @var string
     */
    public string $Message;
}