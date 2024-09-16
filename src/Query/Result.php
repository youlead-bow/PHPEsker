<?php

namespace Esker\Query;

/**
 * Class Result
 * @package Esker\Query
 */
class Result
{
    /**
     * @var string
     */
    public string $noMoreItems;
    /**
     * @var int
     */
    public int $nTransports;
    /**
     * @var [Transport]
     */
    public array $transports;
}