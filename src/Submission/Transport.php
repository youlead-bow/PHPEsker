<?php

namespace Esker\Submission;

/**
 * Class Transport
 * @package Esker\Submission
 */
class Transport
{
    public string $transportName;
    public string $recipientType;
    public int $transportIndex;
    public int $nVars = 0;
    public array $vars;
    public int $nSubnodes = 0;
    public array $subnodes;
    public int $nAttachments = 0;
    public array $attachments;
}