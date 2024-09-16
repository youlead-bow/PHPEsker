<?php

namespace Esker\Session;

use AllowDynamicProperties;
use Esker\Exception\EskerException;
use Esker\Query\SessionHeader;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;

/**
 * Class SessionService
 * @package Esker\Session
 */
class SessionService
{
    public SoapClient $client;
    public mixed $result;
    public ?EskerException $eskerException = null;
    public string $Url;
    public SessionHeader $SessionHeaderValue;
    public array $requestHeaders;

    /**
     * SessionService constructor.
     * @param string $wsdl
     * @param bool $exceptionsMode
     * @throws SoapFault
     */
    public function __construct(string $wsdl, bool $exceptionsMode = false)
    {
        $this->client = new SoapClient($wsdl, [
                'exceptions' => true,
                'encoding' => 'utf-8'
            ]
        );
    }

    /**
     *
     */
    public function _CheckEndPoint(): void
    {
        /*if( $this->Url != $this->client->forceEndpoint )
        {
            $this->client->setEndpoint($this->Url);
            $this->client->useHTTPPersistentConnection();
        }*/
    }

    /**
     * @param string $reserved
     * @return BindingResult
     */
    public function GetBindings(string $reserved): BindingResult
    {
        $this->_CheckEndPoint();
        $bindingResult = new BindingResult();
        $param = ['reserved' => $reserved];
        try {
            $this->result = $this->client->__soapCall('GetBindings', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $bindingResult->sessionServiceLocation = $wrapper->sessionServiceLocation;
            $bindingResult->submissionServiceLocation = $wrapper->submissionServiceLocation;
            $bindingResult->queryServiceLocation = $wrapper->queryServiceLocation;
            $bindingResult->sessionServiceWSDL = $wrapper->sessionServiceWSDL;
            $bindingResult->submissionServiceWSDL = $wrapper->submissionServiceWSDL;
            $bindingResult->queryServiceWSDL = $wrapper->queryServiceWSDL;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = 'Unable to call Esker Bindings Service';
            trigger_error($this->eskerException->Message, E_USER_ERROR);
        }
        return $bindingResult;
    }

    /**
     * @param string $username
     * @param string $password
     * @return LoginResult
     */
    public function login(string $username, string $password): LoginResult
    {
        $this->_CheckEndPoint();
        $loginResult = new LoginResult();
        $param = ['userName' => $username, 'password' => $password];
        try {
            $this->client->__setLocation($this->Url);
            $this->result = $this->client->__soapCall('Login', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $loginResult->sessionID = $wrapper->sessionID;
            $this->SessionHeaderValue = new SessionHeader();
            $this->SessionHeaderValue->sessionID = $loginResult->sessionID;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = 'Unable to call Esker Login Service';
            trigger_error($this->eskerException->Message, E_USER_ERROR);
        }
        return $loginResult;
    }

    /**
     *
     */
    public function logout(): void
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $param = ['' => ''];
        try {
            $this->result = $this->client->__soapCall('Logout', ['parameters' => $param]);
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = 'Unable to call Esker Logout Service';
            trigger_error($this->eskerException->Message, E_USER_ERROR);
        }
    }

    /**
     * @param string $session
     * @return SessionService
     */
    public function setSessionID(string $session): SessionService
    {
        $element = array('sessionID' => $session);
        $this->setHeader('SessionHeaderValue', $element);
        return $this;
    }

    /**
     * @param string $headerName
     * @param string|array $headerValue
     * @return SessionService
     */
    public function setHeader(string $headerName, $headerValue): SessionService
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
            $headers[] = new SoapHeader('urn:SessionService2', $key, new SoapVar($values, SOAP_ENC_OBJECT));
        }
        $this->client->__setSoapHeaders($headers);
        return $this;
    }
}
