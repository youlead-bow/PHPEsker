<?php

namespace Esker\Common;


use Esker\Session\SessionService;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;

class BaseService
{
    public SoapClient $client;
    public array $requestHeaders;

    /**
     * @throws SoapFault
     */
    public function __construct(string $wsdl, bool $traceMode = true, bool $debugMode = false)
    {
        $this->client = new SoapClient($wsdl, [
                'exceptions' => $debugMode,
                'trace' => $traceMode,
                'encoding' => 'utf-8',
            ]
        );
    }

    /**
     * @param string $headerName
     * @param array|string $headerValue
     */
    public function setHeader(string $headerName, array|string $headerValue): void
    {
        if (!isset($this->requestHeaders)) {
            $this->requestHeaders = [$headerName => $headerValue];
        } elseif (array_key_exists($headerName, $this->requestHeaders)) {
            $this->requestHeaders[$headerName] = array_merge($this->requestHeaders[$headerName], $headerValue);
        } else {
            $this->requestHeaders[$headerName] = $headerValue;
        }
        $headers = [];
        foreach ($this->requestHeaders as $key => $values) {
            $headers[] = new SoapHeader(static::soapNS, $key, new SoapVar($values, SOAP_ENC_OBJECT));
        }
        $this->client->__setSoapHeaders($headers);
    }
}