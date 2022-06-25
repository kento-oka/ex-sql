<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Token;

use Kentoka\ExSql\Token;

class PlainToken extends Token
{
    public function __construct(
        public readonly string $plain,
        int $offset
    ) {
        parent::__construct($offset);
    }
}
