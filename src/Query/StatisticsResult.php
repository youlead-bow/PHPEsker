<?php

namespace Esker\Query;

/**
 * Class StatisticsResult
 * @package Esker\Query
 */
class StatisticsResult
{
    public int $nTypes;
    public string $typeName = '';
    public string $typeContent = '';
    public int $nItems;
    public string $includeSubNodes = '';
}