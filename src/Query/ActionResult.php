<?php

namespace Esker\Query;

/**
 * Class ActionResult
 * @package Esker\Query
 */
class ActionResult
{
    public int $nSucceeded;
    public int $nFailed;
    public int $nItem;
    public array $transportIDs;
    public string $errorReason;
}