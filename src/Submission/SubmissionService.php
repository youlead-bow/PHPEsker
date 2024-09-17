<?php

namespace Esker\Submission;

use Esker\Common\BaseService;
use Esker\Exception\EskerException;
use SoapFault;

/**
 * Class SubmissionService
 * @package Esker\Submission
 */
class SubmissionService extends BaseService
{
    const string soapNS = 'urn:SubmissionService2';

    public mixed $result;
    public ?EskerException $eskerException = null;
    public string $Url;
    public SessionHeader $SessionHeaderValue;

    /**
     * SubmissionService constructor.
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
     *
     */
    public function _CheckEndPoint(): void
    {
        $this->client->__setLocation($this->Url);
    }

    public function SubmissionAction(string $name, array $param): Result
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $submissionResult = new Result();

        try {
            $this->result = $this->client->__soapCall($name, ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $submissionResult->submissionID = $wrapper->submissionID;
            $submissionResult->transportID = $wrapper->transportID;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }

        return $submissionResult;
    }

    /**
     * @param string $subject
     * @param BusinessData $document
     * @param BusinessRules $rules
     * @return Result
     */
    public function Submit(string $subject, BusinessData $document, BusinessRules $rules): Result
    {
        $param = ['subject' => $subject, 'document' => $document, 'rules' => $rules];
        return $this->SubmissionAction('Submit', $param);
    }

    /**
     * @param Transport $transport
     * @return Result
     */
    public function SubmitTransport(Transport $transport): Result
    {
        $param = ['transport' => (array)$transport];
        return $this->SubmissionAction('SubmitTransport', $param);
    }

    /**
     * @param string $xml
     * @return Result
     */
    public function SubmitXML(string $xml): Result
    {
        $param = ['xml' => $xml];
        return $this->SubmissionAction('SubmitXML', $param);
    }

    public function ExtractAction(string $name, array $parameters): ExtractionResult
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $extractionResult = new ExtractionResult();

        try {
            $this->result = $this->client->__soapCall($name, ['parameters' => $parameters]);
            $wrapper = $this->result->{'return'};
            $extractionResult->noMoreItems = $wrapper->noMoreItems;
            $extractionResult->nTransports = $wrapper->nTransports;
            $extractionResult->transports = $wrapper->transports;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }

        return $extractionResult;
    }

    /**
     * @param BusinessData $document
     * @param BusinessRules $rules
     * @param ExtractionParameters $param
     * @return ExtractionResult
     */
    public function ExtractFirst(
        BusinessData $document,
        BusinessRules $rules,
        ExtractionParameters $param
    ): ExtractionResult {
        $parameters = ['document' => $document, 'rules' => $rules, 'param' => $param];
        return $this->ExtractAction('ExtractFirst', $parameters);
    }

    /**
     * @param BusinessData $document
     * @param BusinessRules $rules
     * @param ExtractionParameters $param
     * @return ExtractionResult
     */
    public function ExtractNext(
        BusinessData $document,
        BusinessRules $rules,
        ExtractionParameters $param
    ): ExtractionResult {
        $parameters = ['document' => $document, 'rules' => $rules, 'param' => $param];
        return $this->ExtractAction('ExtractNext', $parameters);
    }

    /**
     * @param mixed $inputFile
     * @param ConversionParameters $params
     * @return ConversionResult
     */
    public function ConvertFile(mixed $inputFile, ConversionParameters $params): ConversionResult
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $conversionResult = new ConversionResult();
        $param = array('inputFile' => $inputFile, 'params' => $params);
        try {
            $this->result = $this->client->__soapCall('ConvertFile', array('parameters' => $param));
            $wrapper = $this->result->{'return'};
            $conversionResult->convertedFile = new File();
            $conversionResult->convertedFile->name = $wrapper->convertedFile->name;
            $conversionResult->convertedFile->mode = $wrapper->convertedFile->mode;
            $content = $wrapper->convertedFile->content;
            if ($content !== null) {
                $conversionResult->convertedFile->content = base64_decode($wrapper->convertedFile->content);
            }
            $conversionResult->convertedFile->url = $wrapper->convertedFile->url;
            $conversionResult->convertedFile->storageID = $wrapper->convertedFile->storageID;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $conversionResult;
    }

    /**
     * @param File $wsFile
     * @return string
     */
    public function DownloadFile(File $wsFile): string
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $param = ['wsFile' => $wsFile];
        $resultFile = null;
        try {
            $this->result = $this->client->__soapCall('DownloadFile', ['parameters' => $param]);
            $resultFile = base64_decode($this->result->{'return'});
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $resultFile;
    }

    /**
     * @param File $resource
     * @param string $type
     * @param bool $published
     * @param bool $overwritePrevious
     */
    public function RegisterResource(File $resource, string $type, bool $published, bool $overwritePrevious): void
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $param = [
            'resource' => $resource,
            'type' => $type,
            'published' => $published,
            'overwritePrevious' => $overwritePrevious,
        ];
        try {
            $this->result = $this->client->__soapCall('RegisterResource', array('parameters' => $param));
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
    }

    /**
     * @param string $type
     * @param bool $published
     * @return Resources
     */
    public function ListResources(string $type, bool $published): Resources
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $resources = new Resources();
        $param = ['type' => $type, 'published' => $published];
        try {
            $this->result = $this->client->__soapCall('ListResources', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $resources->nResources = $wrapper->nResources;
            if ($resources->nResources > 1) {
                $resources->resources = $wrapper->resources->{'string'};
            } else {
                $resources->resources = (array)$wrapper->resources->{'string'};
            }
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $resources;
    }

    /**
     * @param string $resourceName
     * @param string $type
     * @param bool $published
     */
    public function DeleteResource(string $resourceName, string $type, bool $published): void
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $param = ['resourceName' => $resourceName, 'type' => $type, 'published' => $published];
        try {
            $this->result = $this->client->__soapCall('DeleteResource', ['parameters' => $param]);
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
    }

    public function UploadFileAction(string $name, array $param): File
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $wsfile = new File();

        try {
            $this->result = $this->client->__soapCall($name, ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $wsfile->name = $wrapper->name;
            $wsfile->mode = $wrapper->mode;
            $wsfile->content = $wrapper->content;
            $wsfile->url = $wrapper->url;
            $wsfile->storageID = $wrapper->storageID;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }

        return $wsfile;
    }

    /**
     * @param string $fileContent
     * @param string $name
     * @return File
     */
    public function UploadFile(string $fileContent, string $name): File
    {
        $param = ['fileContent' => $fileContent, 'name' => $name];
        return $this->UploadFileAction('UploadFile', $param);
    }

    /**
     * @param string $fileContent
     * @param string $destWSFile
     * @return File
     */
    public function UploadFileAppend(string $fileContent, string $destWSFile): File
    {
        $param = ['fileContent' => $fileContent, 'destWSFile' => $destWSFile];
        return $this->UploadFileAction('UploadFileAppend', $param);
    }

    /**
     * @param string $session
     * @return SubmissionService
     */
    public function setSessionID(string $session): SubmissionService
    {
        $element = array('sessionID' => $session);
        $this->setHeader('SessionHeaderValue', $element);
        return $this;
    }
}
