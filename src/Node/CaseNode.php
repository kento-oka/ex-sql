<?php

declare(strict_types=1);

namespace Kentoka\ExSql\Node;

use Kentoka\ExSql\Node;
use LogicException;

class CaseNode extends Node
{
    /**
     * @param _Case[] $cases
     * @param Node[]|null $else_block
     *
     * @phpstan-param list<_Case> $cases
     * @phpstan-param list<Node>|null $else_block
     */
    public function __construct(
        public readonly array $cases,
        public readonly array|null $else_block
    ) {
        if (!array_is_list($cases)) {
            throw new LogicException();
        }

        foreach ($cases as $case) {
            if (!is_object($case) || !$case instanceof _Case) {
                throw new LogicException();
            }
        }

        if ($else_block !== null) {
            if (!array_is_list($else_block)) {
                throw new LogicException();
            }

            foreach ($else_block as $node) {
                if (!is_object($node) || !$node instanceof Node) {
                    throw new LogicException();
                }
            }
        }
    }
}
