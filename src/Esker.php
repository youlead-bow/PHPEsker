<?php

namespace Esker;

use Esker\Common\Constant;
use Esker\Exception\BindingException;
use Esker\Exception\LoginException;
use Esker\Exception\SubmitTransportException;
use Esker\Query\Header;
use Esker\Query\QueryService;
use Esker\Query\Request;
use Esker\Session\SessionService;
use Esker\Submission\Attachment;
use Esker\Submission\File;
use Esker\Submission\Result;
use Esker\Submission\SessionHeader;
use Esker\Submission\SubmissionService;
use Esker\Submission\SVar;
use Esker\Submission\Transport;
use Esker\Submission\TransportAttachments;
use Esker\Submission\TransportVars;

/**
 * Class Esker
 */
class Esker
{
    private QueryService $queryService;
    private SubmissionService $submissionService;
    private Transport $transport;

    /**
     * Esker constructor.
     * @param string $username
     * @param string $password
     * @param bool $debugMode
     * @throws BindingException
     * @throws LoginException
     */
    public function __construct(string $username, string $password, bool $traceMode = true, bool $exceptionsMode = false)
    {;
        $session = new SessionService('https://as1.ondemand.esker.com/EDPWS_D/EDPWS.dll?Handler=GenSession2WSDL');
        $bindings = $session->GetBindings($username);
        if ($session->eskerException) {
            throw new BindingException('Failed call GetBinding  : ' . $session->eskerException->Message);
        }
        $session->Url = $bindings->sessionServiceLocation;
        $login = $session->login($username, $password);
        if ($session->eskerException) {
            throw new LoginException('Failed call Login : ' . $session->eskerException->Message);
        }
        $this->submissionService = new SubmissionService($bindings->submissionServiceWSDL, $traceMode, $exceptionsMode);
        $this->submissionService->Url = $bindings->submissionServiceLocation;
        $this->submissionService->SessionHeaderValue = new SessionHeader();
        $this->submissionService->SessionHeaderValue->sessionID = $login->sessionID;
        $this->queryService = new QueryService($bindings->queryServiceWSDL, $traceMode, $exceptionsMode);
        $this->queryService->Url = $bindings->queryServiceLocation;
        $this->queryService->SessionHeaderValue = new Query\SessionHeader();
        $this->queryService->SessionHeaderValue->sessionID = $login->sessionID;
    }

    /**
     * @param string $transportName
     * @param array $vars
     * @param array $files
     * @param bool $validation
     * @param string $validationMessage
     * @param string $recipientType
     * @return Esker
     */
    public function buildTransport(
        string $transportName,
        array $vars,
        array $files,
        bool $validation = false,
        string $validationMessage = '',
        string $recipientType = ''
    ): Esker {
        $this->transport = new Transport();
        $this->transport->recipientType = $recipientType;
        $this->transport->transportName = $transportName;
        $this->addTransportVariables($vars, $validation, $validationMessage);
        $this->addTransportAttachments($files);
        return $this;
    }

    /**
     * @throws SubmitTransportException
     * @Return Result
     */
    public function submitTransport(): Result
    {
        $results = $this->submissionService->SubmitTransport($this->transport);
        if ($this->submissionService->eskerException || !$this->transport) {
            throw new SubmitTransportException('Failed call SubmitTransport : ' . $this->submissionService->eskerException->Message ?? 'Uninitialized Transport object');
        }
        return $results;
    }

    /**
     * @param string $nom
     * @param string $valeur
     * @return SVar
     */
    private function _buildVariableTag(string $nom, string $valeur): SVar
    {
        $var = new SVar();
        $var->attribute = $nom;
        $var->simpleValue = $valeur;
        $var->type = 'TYPE_STRING';
        return $var;
    }

    /**
     * @param array $variables
     * @param bool $validation
     * @param string $validationMessage
     * @return Esker
     */
    public function addTransportVariables(
        array $variables,
        bool $validation = false,
        string $validationMessage = ''
    ): Esker {
        $this->transport->vars = new TransportVars();
        foreach ($variables as $name => $value) {
            $this->transport->vars->Var[] = $this->_buildVariableTag($name, $value);
        }
        if ($validation) {
            $this->transport->vars->Var[] = $this->_buildVariableTag('NeedValidation', '1');
            $this->transport->vars->Var[] = $this->_buildVariableTag('ValidationMessage',
                utf8_encode($validationMessage));
        }
        return $this;
    }

    /**
     * @param string $file
     * @return File
     */
    private function _readFile(string $file): File
    {
        $wsFile = new File();
        $wsFile->mode = Constant::WSFILE_MODE['MODE_INLINED'];
        $wsFile->name = $this->_getFileName($file);
        $myfile = fopen($file, 'rb');
        $wsFile->content = fread($myfile, filesize($file));
        fclose($myfile);
        return $wsFile;
    }

    /**
     * @param string $file
     * @return Attachment
     */
    private function _buildAttachTag(string $file): Attachment
    {
        $fileTag = new Attachment();
        $fileTag->inputFormat = pathinfo($file, PATHINFO_EXTENSION);
        $fileTag->sourceAttachment = $this->_readFile($file);
        return $fileTag;
    }

    /**
     * @param array $files
     * @return Esker
     */
    public function addTransportAttachments(array $files): Esker
    {
        $this->transport->attachments = new TransportAttachments();
        foreach ($files as $file) {
            $this->transport->attachments->Attachment[] = $this->_buildAttachTag($file);
        }
        return $this;
    }

    /**
     * @param string $sourceString
     * @param string $searchString
     * @return bool|int
     */
    private function _lastIndexOf(string $sourceString, string $searchString)
    {
        $index = strpos(strrev($sourceString), strrev($searchString));
        $index = strlen($sourceString) - strlen($index) - $index;
        return $index;
    }

    /**
     * @param string $filename
     * @return bool|string
     */
    private function _getFileName(string $filename)
    {
        $i = $this->_lastIndexOf($filename, '/');
        if ($i < 0) {
            $i = $this->_lastIndexOf($filename, '\\');
        }
        if ($i < 0) {
            return $filename;
        }
        return substr($filename, $i + 1);
    }

    /**
     * @param string $ruidex
     * @return ?Query\Result
     */
    public function getLetterStatuses(string $ruidex): ?Query\Result
    {
        $this->queryService->QueryHeaderValue = new Header();
        $this->queryService->QueryHeaderValue->recipientType = 'MOD';
        $request = new Request();
        $request->nItems = 1;
        $request->attributes = '';
        $request->filter = '(&(RuidEx=' . $ruidex . '))';
        return $this->queryService->QueryFirst($request);
    }
}
