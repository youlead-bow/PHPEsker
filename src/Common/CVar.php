<?php

namespace Esker\Common;

/**
 * Class CVar
 * @package Esker\Submission
 */
class CVar
{
    public string $attribute;
    public string $type;
    public string $simpleValue;
    public int $nValues = 0;
    public array $multipleStringValues;
    public array $multipleLongValues;
    public array $multipleDoubleValues;
}