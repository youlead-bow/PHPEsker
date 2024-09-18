<?php

namespace Esker\Session;

use Esker\Common\BaseService;
use Esker\Exception\EskerException;
use Esker\Query\SessionHeader;
use SoapFault;

/**
 * Class SessionService
 * @package Esker\Session
 */
class SessionService extends BaseService
{
    const string soapNS = 'urn:SessionService2';

    public ?EskerException $eskerException = null;
    public SessionHeader $SessionHeaderValue;

    /**
     * SessionService constructor.
     * @param string $wsdl
     * @param bool $traceMode
     * @param bool $debugMode
     * @throws SoapFault
     */
    public function __construct(string $wsdl, bool $traceMode = true, bool $debugMode = false)
    {
        parent::__construct($wsdl, $traceMode, $debugMode);
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
}
