<?php

namespace Esker\Common;

use Esker\Submission\File;

/**
 * Class Attachment
 * @package Esker\Common
 */
class Attachment
{
    public string $inputFormat = '';
    public string $outputFormat = '';
    public string $stylesheet = '';
    public string $outputName = '';
    public File $sourceAttachment;
    public int $nConvertedAttachments = 0;
    public array $convertedAttachments = [];
    public int $nVars = 0;
    public array $vars = [];
}