<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Token;

use Kentoka\ExSql\Token;

class CaseToken extends Token
{
    public function __construct(
        public readonly string $var_name,
        int $offset
    ) {
        parent::__construct($offset);
    }
}
