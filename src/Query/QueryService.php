<?php

namespace Esker\Query;

use Esker\Common\BaseService;
use Esker\Exception\EskerException;
use Esker\Submission\File;
use SoapFault;
/**
 * Class QueryService
 * @package Esker\Query
 */
class QueryService extends BaseService
{
    const string soapNS = 'urn:QueryService2';

    public SessionHeader $SessionHeaderValue;
    public Header $QueryHeaderValue;
    public ?EskerException $eskerException = null;
    public string $RESOURCE_TYPE;

    /**
     * QueryService constructor.
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
        $statisticsLine = new StatisticsLine();
        $param = ['filter' => $filter];
        try {
            $this->result = $this->client->__soapCall('QueryStatistics', ['parameters' => $param]);
            $wrapper = $this->result->{'return'};
            $statisticsResult->nTypes = $wrapper->nTypes;
            $statisticsResult->typeName = $wrapper->typeName->{'string'};
            $statisticsLine->nStates = $wrapper->typeContent->StatisticsLine->nStates;
            $statisticsLine->states = $wrapper->typeContent->StatisticsLine->states->{'int'};
            $statisticsLine->counts = $wrapper->typeContent->StatisticsLine->counts->{'int'};
            $statisticsResult->typeContent = $statisticsLine;
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }
        return $statisticsResult;
    }

    public function QueryAction(string $action, string $identifier, ?Parameters $params = null, ?string $reason = null): ActionResult
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $actionResult = new ActionResult();
        $param = ['identifier' => $identifier];
        if(!is_null($params)){
            $param['params'] = $params;
        }
        if(!is_null($reason)){
            $param['reason'] = $reason;
        }

        try {
            $this->result = $this->client->__soapCall($action, ['parameters' => $param]);
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

    public function DownloadFileAction(string $name, array $param): string
    {
        $this->_CheckEndPoint();
        $this->setQueryHeader();
        $resultFile = '';

        try {
            $this->result = $this->client->__soapCall($name, ['parameters' => $param]);
            $resultFile = $this->result->{'return'};
            $this->eskerException = null;
        } catch (SoapFault $fault) {
            $this->eskerException = new EskerException();
            $this->eskerException->Message = $fault->faultstring;
        }

        return $resultFile;
    }

    /**
     * @param string $wsFile
     * @return string
     */
    public function DownloadFile(string $wsFile): string
    {
        $param = ['wsFile' => $wsFile];
        return $this->DownloadFileAction('DownloadFile', $param);
    }

    /**
     * @param File $wsFile
     * @param int $uPos
     * @param int $uChunkSize
     * @return string
     */
    public function DownloadFileChunk(File $wsFile, int $uPos, int $uChunkSize): string
    {
        $param = ['wsFile' => $wsFile, 'uPos' => $uPos, 'uChunkSize' => $uChunkSize];
        return $this->DownloadFileAction('DownloadFileChunck', $param);
    }

    /**
     * @param mixed $wrapper
     * @return Result
     */
    public function getQueryResult(mixed $wrapper): Result
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
    public function getAttachments(mixed $wrapper): Attachments
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
}
