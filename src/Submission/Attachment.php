<?php

namespace Esker\Submission;

/**
 * Class Attachment
 * @package Esker\Submission
 */
class Attachment
{
    public string $inputFormat;
    public string $outputFormat;
    public string $stylesheet;
    public string $outputName;
    public File $sourceAttachment;
    public int $nConvertedAttachments = 0;
    public array $convertedAttachments;
}