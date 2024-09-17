<?php

namespace Esker\Query;

/**
 * Class Result
 * @package Esker\Query
 */
class Result
{
    public bool $noMoreItems;
    public int $nTransports = 0;
    public array $transports = [];
}