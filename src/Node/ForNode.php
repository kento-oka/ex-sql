<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Node;

use Kentoka\ExSql\Node;
use LogicException;

class ForNode extends Node
{
    /**
     * @param string $iterate_var_name
     * @param string|null $scope_var_name
     * @param string|null $glue
     * @param Node[] $block
     *
     * @phpstan-param non-empty-string $iterate_var_name
     * @phpstan-param non-empty-string|null $scope_var_name
     * @phpstan-param list<Node> $block
     */
    public function __construct(
        public readonly string $iterate_var_name,
        public readonly string|null $scope_var_name,
        public readonly string|null $glue,
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
