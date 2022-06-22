<?php

namespace Kentoka\ExSql\Tests\Unit\Route;

use Kentoka\ExSql\ExSql;
use PHPUnit\Framework\TestCase;

class TokenizeTest extends TestCase
{
    /**
     * @dataProvider dataProvider_reversibility
     */
    public function test_reversibility(string $query): void
    {
        $tokens = ExSql::tokenize($query);

        foreach ($tokens as $token) {
            self::assertIsArray($token);
            self::assertArrayHasKey(ExSql::TKEY_PLAIN, $token);
            self::assertTrue(is_string($token[ExSql::TKEY_PLAIN]));
            self::assertTrue($token[ExSql::TKEY_PLAIN] !== '');
        }
var_dump($tokens);die;
        self::assertSame($query, implode('', array_column($tokens, ExSql::TKEY_PLAIN)));
    }

    /**
     * @return list<array{string}>
     */
    public function dataProvider_reversibility(): array
    {
        $queries = [
            <<<QUERY
            SELECT *
            FROM `test`
            WHERE
                %case :x
                    %for[AND] scope of :x
                        :scope.c@column = :scope.v@string
                    %endfor
                %case :y
                    a IN (%for[,] :y :y@int %endfor)
                %endcase
            ORDER BY :o@column ASC
            QUERY,
        ];

        return array_map(fn($query) => [$query], $queries);
    }
}
