<?php

namespace Esker\Submission;

/**
 * Class SubNode
 * @package Esker\Submission
 */
class SubNode
{
    public string $name;
    public string $relativeName;
    public int $nSubnodes;
    public array $subNodes;
    public int $nVars = 0;
    public array $vars;
}