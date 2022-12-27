<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Node;

use InvalidArgumentException;
use Kentoka\ExSql\Node;
use LogicException;

class _Case
{
    /**
     * @param string $condition_var_name
     * @param Node[] $block
     *
     * @phpstan-param non-empty-string $condition_var_name
     * @phpstan-param list<Node> $block
     */
    public function __construct(
        public readonly string $condition_var_name,
        public readonly array $block
    ) {
        if (!array_is_list($block)) {
            throw new LogicException();
        }

        foreach ($block as $node) {
            if (!is_object($node) || !$node instanceof Node) {
                throw new LogicException();
            }
        }
    }
}
