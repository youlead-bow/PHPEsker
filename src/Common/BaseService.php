<?php

namespace Esker\Common;


use Esker\Exception\EskerException;
use Esker\Query\QueryService;
use Esker\Query\SessionHeader;
use Esker\Session\SessionService;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;

class BaseService
{
    public SoapClient $client;
    public array $requestHeaders;
    public string $Url;
    public SessionHeader $SessionHeaderValue;
    public ?EskerException $eskerException = null;
    protected mixed $result;
    protected array $soapHeaders;

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

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function _CheckEndPoint(): void
    {
        if(get_called_class() === SessionService::class){
            return;
        }

        $this->client->__setLocation($this->Url);
    }

    /**
     * @param string $session
     */
    public function setSessionID(string $session): void
    {
        $element = ['sessionID' => $session];
        $this->setHeader('SessionHeaderValue', $element);
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

        if(get_called_class() === QueryService::class) {
            $this->soapHeaders = $headers;
        } else {
            $this->client->__setSoapHeaders($headers);
        }
    }
}