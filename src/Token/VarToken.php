<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Token;

use Kentoka\ExSql\Token;

class VarToken extends Token
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        int $offset
    ) {
        parent::__construct($offset);
    }
}
