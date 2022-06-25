<?php

declare(strict_types=1);

namespace Kentoka\ExSql;

abstract class Token
{
    public function __construct(
        public readonly int $offset
    ) {
    }
}
