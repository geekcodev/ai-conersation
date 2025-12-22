<?php

declare(strict_types=1);

namespace App\Base;

use App\Interfaces\RepositoryInterface;


abstract class BaseRepository implements RepositoryInterface
{
    protected string $modelClass;

    abstract protected function getModel();
}
