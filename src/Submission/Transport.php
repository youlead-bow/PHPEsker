<?php

namespace Esker\Submission;

use Esker\Query\Attachments;

/**
 * Class Transport
 * @package Esker\Submission
 */
class Transport
{
    public string $transportName;
    public string $recipientType;
    public string $transportIndex = '';
    public int $nVars = 0;
    public TransportVars $vars;
    public int $nSubnodes = 0;
    public array $subnodes;
    public int $nAttachments = 0;
    public TransportAttachments $attachments;
}