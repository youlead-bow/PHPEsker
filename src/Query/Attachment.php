<?php

namespace Esker\Query;

/**
 * Class Attachment
 * @package Esker\Query
 */
class Attachment
{
    public string $inputFormat;
    public string $outputFormat;
    public string $stylesheet;
    public string $outputName;
    public string $sourceAttachment;
    public int $nConvertedAttachments;
    public array $convertedAttachments;
}