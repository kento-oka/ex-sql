<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Token;

use Kentoka\ExSql\Token;

class VarToken extends Token
{
    public function __construct(
        public readonly string $var_name,
        public readonly string $var_type,
        int $offset
    ) {
        parent::__construct($offset);
    }
}
