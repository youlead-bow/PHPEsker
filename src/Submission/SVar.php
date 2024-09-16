<?php

namespace Esker\Submission;

/**
 * Class SVar
 * @package Esker\Submission
 */
class SVar
{
    public string $attribute;
    public string $type;
    public string $simpleValue;
    public int $nValues = 0;
    public array $multipleStringValues;
    public array $multipleLongValues;
    public array $multipleDoubleValues;
}