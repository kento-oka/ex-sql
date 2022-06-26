<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Token;

use Kentoka\ExSql\Token;

class ForToken extends Token
{
    public function __construct(
        public readonly string|null $glue,
        public readonly string|null $scope_var_name,
        public readonly string $iterate_var_name,
        int $offset
    ) {
        parent::__construct($offset);
    }
}
