<?php

namespace Esker;

use Esker\Common\Attachment;
use Esker\Common\Constant;
use Esker\Common\CVar;
use Esker\Exception\BindingException;
use Esker\Exception\LoginException;
use Esker\Exception\SubmitTransportException;
use Esker\Query\Header as QueryHeader;
use Esker\Query\Parameters;
use Esker\Query\QueryService;
use Esker\Query\Request;
use Esker\Session\Header;
use Esker\Session\SessionService;
use Esker\Submission\File;
use Esker\Submission\Result;
use Esker\Submission\SubmissionService;
use Esker\Submission\Transport;
use Esker\Submission\TransportAttachments;
use Esker\Submission\TransportVars;

/**
 * Class Esker
 */
class Esker
{
    private bool $query = false;
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
    {
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
        $this->submissionService->SessionHeaderValue = new Header();
        $this->submissionService->SessionHeaderValue->sessionID = $login->sessionID;
        $this->queryService = new QueryService($bindings->queryServiceWSDL, $traceMode, $exceptionsMode);
        $this->queryService->Url = $bindings->queryServiceLocation;
        $this->queryService->SessionHeaderValue = new Header();
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
     * @return CVar
     */
    private function _buildVariableTag(string $nom, string $valeur): CVar
    {
        $var = new CVar();
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
            $this->transport->vars->Var[] = $this->_buildVariableTag('ValidationMessage', mb_convert_encoding($validationMessage, 'UTF-8', 'ISO-8859-1'));
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
    private function _lastIndexOf(string $sourceString, string $searchString): bool|int
    {
        $index = strpos(strrev($sourceString), strrev($searchString));
        $index = strlen($sourceString) - strlen($index) - $index;
        return $index;
    }

    /**
     * @param string $filename
     * @return bool|string
     */
    private function _getFileName(string $filename): bool|string
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

    private function setQueryHeader(): void
    {
        $this->query = true;
        $this->queryService->QueryHeaderValue = new QueryHeader();
        $this->queryService->QueryHeaderValue->recipientType = 'MOD';
    }

    /**
     * @param string $ruidex
     * @return ?Query\Result
     */
    public function getLetterStatuses(string $ruidex): ?Query\Result
    {
        $this->setQueryHeader();
        $request = new Request();
        $request->nItems = 1;
        $request->attributes = '';
        $request->filter = '(&(RuidEx=' . $ruidex . '))';
        return $this->queryService->QueryFirst($request);
    }

    public function getStatistics(string $ruidex): Query\StatisticsResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryStatistics('(&(RuidEx=' . $ruidex . '))');
    }

    public function getAttachments(string $identifier, string $filter, string $mode): Query\Attachments
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAttachments($identifier, $filter, $mode);
    }

    public function delete(string $identifier): Query\ActionResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAction('Delete', $identifier);
    }

    public function cancel(string $identifier): Query\ActionResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAction('Cancel', $identifier);
    }

    public function resubmit(string $identifier, Parameters $params): Query\ActionResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAction('Resubmit', $identifier, $params);
    }

    public function update(string $identifier, Parameters $params): Query\ActionResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAction('Update', $identifier, $params);
    }

    public function approve(string $identifier, string $reason): Query\ActionResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAction('Approve', $identifier, reason: $reason);
    }

    public function reject(string $identifier, string $reason): Query\ActionResult
    {
        $this->setQueryHeader();
        return $this->queryService->QueryAction('Reject', $identifier, reason: $reason);
    }

    public function getRawResult(): mixed
    {
        if(!$this->query) {
            return $this->submissionService->getResult();
        }

        return $this->queryService->getResult();
    }
}
