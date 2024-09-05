<?php

namespace Esker\Submission;

use Esker\Exception\EskerException;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;

/**
 * Class SubmissionService
 * @package Esker\Submission
 */
class SubmissionService
{
    /**
     * @var SoapClient
     */
    public $client;
    /**
     * @var mixed
     */
    public $result;
    /**
     * @var ?EskerException
     */
    public $eskerException = null;
    /**
     * @var string
     */
    public $Url;
    /**
     * @var SessionHeader
     */
    public $SessionHeaderValue;

    /**
     * SubmissionService constructor.
     * @param string $wsdl
     * @param bool $debugMode
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
     *
     */
    public function _CheckEndPoint(): void
    {
        $this->client->__setLocation($this->Url);
    }

    /**
     * @param string $subject
     * @param BusinessData $document
     * @param BusinessRules $rules
     * @return Result
     */
    public function Submit(string $subject, BusinessData $document, BusinessRules $rules): Result
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $submissionResult = new Result();
        $param = ['subject' => $subject, 'document' => $document, 'rules' => $rules];
        try {
            $this->result = $this->client->__soapCall('Submit', ['parameters' => $param]);
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
     * @param Transport $transport
     * @return Result
     */
    public function SubmitTransport(Transport $transport): Result
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $submissionResult = new Result();
        $param = ['transport' => (array)$transport];
        try {
            $this->result = $this->client->__soapCall('SubmitTransport', ['parameters' => $param]);
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
     * @param string $xml
     * @return Result
     */
    public function SubmitXML(string $xml): Result
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $submissionResult = new Result();
        $param = ['xml' => $xml];
        try {
            $this->result = $this->client->__soapCall('SubmitXML', ['parameters' => $param]);
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
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $extractionResult = new ExtractionResult();
        $parameters = ['document' => $document, 'rules' => $rules, 'param' => $param];
        try {
            $this->result = $this->client->__soapCall('ExtractFirst', ['parameters' => $parameters]);
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
    public function ExtractNext(
        BusinessData $document,
        BusinessRules $rules,
        ExtractionParameters $param
    ): ExtractionResult {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $extractionResult = new ExtractionResult();
        $parameters = ['document' => $document, 'rules' => $rules, 'param' => $param];
        try {
            $this->result = $this->client->__soapCall('ExtractNext', ['parameters' => $parameters]);
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
     * @param mixed $inputFile
     * @param ConversionParameters $params
     * @return ConversionResult
     */
    public function ConvertFile($inputFile, ConversionParameters $params): ConversionResult
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $conversionResult = new ConversionResult();
        $param = array('inputFile' => $inputFile, 'params' => $params);
        try {
            $this->result = $this->client->__soapCall('ConvertFile', array('parameters' => $param));
            $wrapper = $this->result->{'return'};
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
            'overwritePrevious' => $overwritePrevious
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

    /**
     * @param string $fileContent
     * @param string $name
     * @return File
     */
    public function UploadFile(string $fileContent, string $name): File
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $wsfile = new File();
        $param = ['fileContent' => $fileContent, 'name' => $name];
        try {
            $this->result = $this->client->__soapCall('UploadFile', ['parameters' => $param]);
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
     * @param string $destWSFile
     * @return File
     */
    public function UploadFileAppend(string $fileContent, string $destWSFile): File
    {
        $this->_CheckEndPoint();
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $wsfile = new File();
        $param = ['fileContent' => $fileContent, 'destWSFile' => $destWSFile];
        try {
            $this->result = $this->client->__soapCall('UploadFileAppend', ['parameters' => $param]);
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
     * @param string $session
     * @return SubmissionService
     */
    public function setSessionID(string $session): SubmissionService
    {
        $element = array('sessionID' => $session);
        $this->setHeader('SessionHeaderValue', $element);
        return $this;
    }

    /**
     * @param string $headerName
     * @param string|array $headerValue
     * @return SubmissionService
     */
    public function setHeader(string $headerName, $headerValue): SubmissionService
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
            $headers[] = new SoapHeader('urn:SubmissionService2', $key, new SoapVar($values, SOAP_ENC_OBJECT));
        }
        $this->client->__setSoapHeaders($headers);
        return $this;
    }
}
