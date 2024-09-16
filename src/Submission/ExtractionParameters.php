<?php

namespace Esker\Submission;

/**
 * Class ExtractionParameters
 * @package Esker\Submission
 */
class ExtractionParameters
{
    public int $nItems = 0;
    public string $fullPreviewMode;
    public string $attachmentFilter;
    public string $outputFileMode;
    public string $includeSubNodes = 'false';
    public int $startIndex;
}