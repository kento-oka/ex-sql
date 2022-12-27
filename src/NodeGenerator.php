<?php

declare(strict_types=1);

namespace Kentoka\ExSql;

class NodeGenerator
{
    /**
     *
     *
     * @param Token[] $tokens
     * @return Node
     *
     * @phpstan-param list<Token> $tokens
     */
    public function generate(array $tokens): Node
    {
        $reversed_tokens = array_reverse($tokens);


    }
}
