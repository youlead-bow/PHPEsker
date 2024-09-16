<?php

namespace Esker\Submission;

/**
 * Class ExtractionHeader
 * @package Esker\Submission
 */
class ExtractionHeader
{
    public string $ExtractionJobID;
    public string $ExtractionDocID;
    public int $offset;
    public int $transportIndex;
}