<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Node;

use Kentoka\ExSql\Node;

class PlainNode extends Node
{
    /**
     * @phpstan-param string $plain
     */
    public function __construct(
        public readonly string $plain
    ) {
    }
}
