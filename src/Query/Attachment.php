<?php

namespace Esker\Query;

use Esker\Submission\File;

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
    public File $sourceAttachment;
    public int $nConvertedAttachments;
    public array $convertedAttachments;
    public int $nVars = 0;
    public array $vars;
}