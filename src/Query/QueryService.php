<?php

namespace Esker\Query;

use Esker\Exception\EskerException;
use Esker\Submission\File;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;

/**
 * Class QueryService
 * @package Esker\Query
 */
class QueryService
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
     * @var string
     */
    public $Url;
    /**
     * @var SessionHeader
     */
    public $SessionHeaderValue;
    /**
     * @var Header
     */
    public $QueryHeaderValue;
    /**
     * @var ?EskerException
     */
    public $eskerException = null;
    /**
     * @var string
     */
    public $RESOURCE_TYPE;
    /**
     * @var array
     */
    private $soapHeaders;

    /**
     * QueryService constructor.
     * @param string $wsdl
     * @param bool $debugMode
     */
    public function __construct(string $wsdl, bool $traceMode = true, bool $exceptionsMode = false)
    {
        $this->client = new SoapClient($wsdl, [
                'trace' => $traceMode,
                'exceptions' => $exceptionsMode,
                'encoding' => 'utf-8'
            ]
        );
    }

    /**
     * @Return void
     */
    private function _CheckEndPoint(): void
    {
        $this->client->__setLocation($this->Url);
    }

    /**
     * @param Request $request
     * @return ?Result
     */
    public function QueryFirst(Request $request): ?Result
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $param = ['request' => (array)$request];
        try {
            $this->result = $this->client->__soapCall('QueryFirst', ['parameters' => $param]);
            $queryResult = $this->getQueryResult($this->result->{'return'});
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
            return null;
        }
        $this->QueryHeaderValue = new Header();
        $response = $this->client->__getLastResponse();
        $pos1 = strpos($response, '<queryID>');
        $pos2 = strpos($response, '</queryID>');
        if ($pos1 >= 0 && $pos2 > ($pos1 + 9)) {
            $this->QueryHeaderValue->queryID = substr($response, $pos1 + 9, $pos2 - ($pos1 + 9));
        } else {
            $this->QueryHeaderValue->queryID = '';
        }
        $lastRequest = $this->client->__getLastRequest();
        $pos3 = strpos($lastRequest, '<recipientType>');
        $pos4 = strpos($lastRequest, '</recipientType>');
        if ($pos3 >= 0 && $pos4 > ($pos1 + 15)) {
            $this->QueryHeaderValue->recipientType = substr($lastRequest, $pos3 + 15, $pos4 - ($pos3 + 15));
        } else {
            $this->QueryHeaderValue->recipientType = '';
        }
        return $queryResult;
    }

    /**
     * @param Request $request
     * @return ?Result
     */
    public function QueryNext(Request $request): ?Result
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $param = ['request' => $request];
        try {
            $this->result = $this->client->__soapCall('QueryNext', ['parameters' => $param]);
            $queryResult = $this->getQueryResult($this->result->{'return'});
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
            return null;
        }
        return $queryResult;
    }

    /**
     * @param Request $request
     * @return ?Result
     */
    public function QueryLast(Request $request): ?Result
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $param = ['request' => $request];
        try {
            $this->result = $this->client->__soapCall('QueryLast', ['parameters' => $param]);
            $queryResult = $this->getQueryResult($this->result->{'return'});
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
            return null;
        }
        $this->QueryHeaderValue = new Header;
        $response = $this->client->__getLastResponse();
        $pos1 = strpos($response, '<queryID>');
        $pos2 = strpos($response, '</queryID>');
        if ($pos1 >= 0 && $pos2 > ($pos1 + 9)) {
            $this->QueryHeaderValue->queryID = substr($response, $pos1 + 9, $pos2 - ($pos1 + 9));
        } else {
            $this->QueryHeaderValue->queryID = '';
        }
        $lastRequest = $this->client->__getLastRequest();
        $pos3 = strpos($lastRequest, '<recipientType>');
        $pos4 = strpos($lastRequest, '</recipientType>');
        if ($pos3 >= 0 && $pos4 > ($pos1 + 15)) {
            $this->QueryHeaderValue->recipientType = substr($lastRequest, $pos3 + 15, $pos4 - ($pos3 + 15));
        } else {
            $this->QueryHeaderValue->recipientType = '';
        }
        return $queryResult;
    }

    /**
     * @param Request $request
     * @return Result
     */
    public function QueryPrevious(Request $request): Result
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $queryResult = new Result();
        $param = ['request' => $request];
        try {
            $this->result = $this->client->__soapCall('QueryPrevious', ['parameters' => $param]);
            $queryResult = $this->getQueryResult($this->result->{'return'});
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $queryResult;
    }

    /**
     * @param string $transportID
     * @param string $eFilter
     * @param string $eMode
     * @return Attachments
     */
    public function QueryAttachments(string $transportID, string $eFilter, string $eMode): Attachments
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $param = ['transportID' => $transportID, 'eFilter' => $eFilter, 'eMode' => $eMode];
        $attachments = new Attachments();
        try {
            $this->result = $this->client->__soapCall('QueryAttachments', ['parameters' => $param]);
            $attachments = $this->getAttachments($this->result->{'return'});
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $attachments;
    }

    /**
     * @param string $filter
     * @return StatisticsResult
     */
    public function QueryStatistics(string $filter): StatisticsResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $statisticsResult = new StatisticsResult();
        $param = ['filter' => $filter];
        try {
            $this->result = $this->client->__soapCall('QueryStatistics', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $statisticsResult->nTypes = $wrapper->nTypes;
            $statisticsResult->typeName = $wrapper->typeName;
            $statisticsResult->typeContent = $wrapper->typeContent;
            $statisticsResult->nItems = $wrapper->nItems;
            $statisticsResult->includeSubNodes = $wrapper->includeSubNodes;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $statisticsResult;
    }

    /**
     * @param string $identifier
     * @return ActionResult
     */
    public function Delete(string $identifier): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier];
        try {
            $this->result = $this->client->__soapCall('Delete', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $actionResult->nSucceeded = $wrapper->nSucceeded;
            $actionResult->nFailed = $wrapper->nFailed;
            $actionResult->nItem = $wrapper->nItem;
            $actionResult->transportIDs = $wrapper->transportIDs;
            $actionResult->errorReason = $wrapper->errorReason;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $actionResult;
    }

    /**
     * @param string $identifier
     * @return ActionResult
     */
    public function Cancel(string $identifier): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier];
        try {
            $this->result = $this->client->__soapCall('Cancel', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $actionResult->nSucceeded = $wrapper->nSucceeded;
            $actionResult->nFailed = $wrapper->nFailed;
            $actionResult->nItem = $wrapper->nItem;
            $actionResult->transportIDs = $wrapper->transportIDs;
            $actionResult->errorReason = $wrapper->errorReason;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $actionResult;
    }

    /**
     * @param string $identifier
     * @param ResubmitParameters $params
     * @return ActionResult
     */
    public function Resubmit(string $identifier, ResubmitParameters $params): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier, 'params' => $params];
        try {
            $this->result = $this->client->__soapCall('Resubmit', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $actionResult->nSucceeded = $wrapper->nSucceeded;
            $actionResult->nFailed = $wrapper->nFailed;
            $actionResult->nItem = $wrapper->nItem;
            $actionResult->transportIDs = $wrapper->transportIDs;
            $actionResult->errorReason = $wrapper->errorReason;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $actionResult;
    }

    /**
     * @param string $identifier
     * @param UpdateParameters $params
     * @return ActionResult
     */
    public function Update(string $identifier, UpdateParameters $params): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier, 'params' => $params];
        try {
            $this->result = $this->client->__soapCall('Update', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $actionResult->nSucceeded = $wrapper->nSucceeded;
            $actionResult->nFailed = $wrapper->nFailed;
            $actionResult->nItem = $wrapper->nItem;
            $actionResult->transportIDs = $wrapper->transportIDs;
            $actionResult->errorReason = $wrapper->errorReason;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $actionResult;
    }

    public function Approve(string $identifier, string $reason): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier, 'reason' => $reason];
        try {
            $this->result = $this->client->__soapCall('Approve', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $actionResult->nSucceeded = $wrapper->nSucceeded;
            $actionResult->nFailed = $wrapper->nFailed;
            $actionResult->nItem = $wrapper->nItem;
            $actionResult->transportIDs = $wrapper->transportIDs;
            $actionResult->errorReason = $wrapper->errorReason;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $actionResult;
    }

    public function Reject(string $identifier, string $reason): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier, 'reason' => $reason];
        try {
            $this->result = $this->client->__soapCall('Reject', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $actionResult->nSucceeded = $wrapper->nSucceeded;
            $actionResult->nFailed = $wrapper->nFailed;
            $actionResult->nItem = $wrapper->nItem;
            $actionResult->transportIDs = $wrapper->transportIDs;
            $actionResult->errorReason = $wrapper->errorReason;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $actionResult;
    }

    /**
     * @param string $wsFile
     * @return string
     */
    public function DownloadFile(string $wsFile): string
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $resultFile = '';
        $param = ['wsFile' => $wsFile];
        try {
            $this->result = $this->client->__soapCall('DownloadFile', ['parameters' => $param]);
            $resultFile = $this->result->{'return'};
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $resultFile;
    }

    /**
     * @param File $wsFile
     * @param int $uPos
     * @param int $uChunkSize
     * @return string
     */
    public function DownloadFileChunk(File $wsFile, int $uPos, int $uChunkSize): string
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $resultFile = '';
        $param = ['wsFile' => $wsFile, 'uPos' => $uPos, 'uChunkSize' => $uChunkSize];
        try {
            $this->result = $this->client->__soapCall('DownloadFileChunck', ['parameters' => $param]);
            $resultFile = $this->result->{'return'};
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $resultFile;
    }

    /**
     * @param mixed $wrapper
     * @return Result
     */
    public function getQueryResult($wrapper): Result
    {
        $queryResult = new Result();
        $queryResult->noMoreItems = $wrapper->noMoreItems;
        $queryResult->nTransports = $wrapper->nTransports;
        for ($i = 0; $i < $queryResult->nTransports; $i++) {
            if ($queryResult->nTransports > 1) {
                $queryResult->transports[$i] = (object)$wrapper->transports->Transport[$i];
            } else {
                $queryResult->transports[$i] = (object)current($wrapper->transports);
            }
            if ($queryResult->transports[$i]->nVars > 1) {
                $vars = current($queryResult->transports[$i]->vars);
                $my_vars = [];
                for ($j = 0; $j < $queryResult->transports[$i]->nVars; $j++) {
                    $my_vars[] = (object)current($vars);
                    next($vars);
                }
                $queryResult->transports[$i]->vars = $my_vars;
            } else {
                $queryResult->transports[$i]->vars = [(object)$queryResult->transports[$i]->vars->{'Var'}];
            }
        }
        return $queryResult;
    }

    /**
     * @param mixed $wrapper
     * @return Attachments
     */
    public function getAttachments($wrapper): Attachments
    {
        $attachments = new Attachments();
        $attachments->nAttachments = $wrapper->nAttachments;
        if ($attachments->nAttachments > 1) {
            $attachments->attachments = $wrapper->attachments->Attachment;
        } else {
            $attachments->attachments[0] = (object)$wrapper->attachments->Attachment;
        }
        for ($i = 0; $i < $attachments->nAttachments; $i++) {
            $attachments->attachments[$i] = (object)$attachments->attachments[$i];
            $attachments->attachments[$i]->sourceAttachment = (object)$attachments->attachments[$i]->sourceAttachment;
            $convertedAttachments = $attachments->attachments[$i]->convertedAttachments;
            for ($j = 0; $j < $attachments->attachments[$i]->nConvertedAttachments; $j++) {
                if (is_array($convertedAttachments)) {
                    $attachments->attachments[$i]->convertedAttachments[$j] = (object)current($convertedAttachments);
                    next($convertedAttachments);
                } else {
                    break;
                }
            }
        }
        return $attachments;
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
     * @param string $query
     */
    public function setQueryID(string $query): void
    {
        $element = ['queryID' => $query];
        $this->setHeader('QueryHeaderValue', $element);
    }

    /**
     * @param string $recipientType
     */
    public function setQueryRecipientType(string $recipientType): void
    {
        $element = ['recipientType' => $recipientType];
        $this->setHeader('QueryRecipientTypeValue', $element);
    }

    /**
     *
     */
    public function setQueryHeader(): void
    {
        $this->setSessionID($this->SessionHeaderValue->sessionID);
        $this->setQueryRecipientType($this->QueryHeaderValue->recipientType);
        if ($this->QueryHeaderValue->queryID) {
            $this->setQueryID($this->QueryHeaderValue->queryID);
        }
        $this->client->__setSoapHeaders([]);
        $this->client->__setSoapHeaders($this->soapHeaders);
    }


    /**
     * @param string $headerName
     * @param string|array $headerValue
     */
    public function setHeader(string $headerName, $headerValue): void
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
            $headers[] = new SoapHeader('urn:QueryService2', $key, new SoapVar($values, SOAP_ENC_OBJECT), false);
        }
        $this->soapHeaders = $headers;
    }
}
