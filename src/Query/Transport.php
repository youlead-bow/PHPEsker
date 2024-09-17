<?php

namespace Esker\Query;

/**
 * Class Transport
 * @package Esker\Query
 */
class Transport
{
    public string $transportID = '';
    public string $transportName = '';
    public string $recipientType = '';
    public string $state;
    public int $nVars;
    public array $vars;
    public int $nSubnodes;
    public array $subnodes;
}