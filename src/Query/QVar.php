<?php

namespace Esker\Query;

/**
 * Class QVar
 * @package Esker\Query
 */
class QVar
{
    public string $attribute;
    public string $type;
    public string $simpleValue;
    public int $nValues;
    public array $multipleStringValues;
    public array $multipleLongValues;
    public array $multipleDoubleValues;
}