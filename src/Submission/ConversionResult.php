<?php

namespace Esker\Submission;

/**
 * Class ConversionResult
 * @package Esker\Submission
 */
class ConversionResult
{
    public File $convertedFile;

    public function __construct()
    {
        $this->convertedFile = new File();
    }


}