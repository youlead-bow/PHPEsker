<?php

namespace Esker\Query;

use Esker\Common\Constant;

/**
 * Class Request
 * @package Esker\Query
 */
class Request
{
    public string $filter = '';
    public string $sortOrder = '';
    public string $attributes = '';
    public int $nItems;
    public string $includeSubNodes = 'false';
    public string $searchInArchive = 'false';
    public mixed $fileRefMode = Constant::WSFILE_MODE['MODE_INLINED'];
}