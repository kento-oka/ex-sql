<?php

namespace Kentoka\ExSql\Tests\Unit\Route;

use Kentoka\ExSql\ExSql;
use Kentoka\ExSql\Token;
use Kentoka\ExSql\Token\CaseToken;
use Kentoka\ExSql\Token\EndCaseToken;
use Kentoka\ExSql\Token\EndForToken;
use Kentoka\ExSql\Token\ForToken;
use Kentoka\ExSql\Token\PlainToken;
use Kentoka\ExSql\Token\VarToken;
use PHPUnit\Framework\TestCase;

class TokenizeTest extends TestCase
{
    /**
     * @dataProvider dataProvider_tokenize
     *
     * @param string $query
     * @param Token[] $expected
     */
    public function test_tokenize(string $query, array $expected): void
    {
        $tokens = ExSql::tokenize($query);

        foreach ($tokens as $token) {
            self::assertInstanceOf(Token::class, $token);
        }

        self::assertCount(count($expected), $tokens);
        self::assertTrue(array_is_list($tokens));

        foreach ($expected as $i => $expected_token) {
            self::assertEquals($expected_token, $tokens[$i]);
        }
    }

    /**
     * @return iterable<string,array{string,list<Token>}>
     */
    public function dataProvider_tokenize(): iterable
    {
        yield '' => [
            <<<QUERY
            SELECT *
            FROM `test`
            WHERE
                %case :x
                    %for[AND] :x
                        :a@column = :b@string
                    %endfor
                %case :y
                    y IN (%for scope of :y :scope@int %endfor)
                %endcase
            ORDER BY :o@column ASC
            QUERY,
            [
                new PlainToken(plain: "SELECT *\nFROM `test`\nWHERE\n    ", offset: 0),
                new CaseToken(var_name: 'x', offset: 31),
                new PlainToken(plain: "\n        ", offset: 39),
                new ForToken(glue: 'AND', scope_var_name: null, iterate_var_name: 'x', offset: 48),
                new PlainToken(plain: "\n            ", offset: 60),
                new VarToken(name: 'a', type: 'column', offset: 73),
                new PlainToken(plain: " = ", offset: 82),
                new VarToken(name: 'b', type: 'string', offset: 85),
                new PlainToken(plain: "\n        ", offset: 94),
                new EndForToken(offset: 103),
                new PlainToken(plain: "\n    ", offset: 110),
                new CaseToken(var_name: 'y', offset: 115),
                new PlainToken(plain: "\n        y IN (", offset: 123),
                new ForToken(glue: null, scope_var_name: 'scope', iterate_var_name: 'y', offset: 138),
                new PlainToken(plain: ' ', offset: 154),
                new VarToken(name: 'scope', type: 'int', offset: 155),
                new PlainToken(plain: ' ', offset: 165),
                new EndForToken(offset: 166),
                new PlainToken(plain: ")\n    ", offset: 173),
                new EndCaseToken(offset: 179),
                new PlainToken(plain: "\nORDER BY ", offset: 187),
                new VarToken(name: 'o', type: 'column', offset: 197),
                new PlainToken(plain: " ASC", offset: 206),
            ]
        ];
    }
}
