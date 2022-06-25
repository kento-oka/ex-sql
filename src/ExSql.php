<?php

declare(strict_types=1);

namespace Kentoka\ExSql;

use Kentoka\ExSql\Token\CaseToken;
use Kentoka\ExSql\Token\ElseToken;
use Kentoka\ExSql\Token\EndCaseToken;
use Kentoka\ExSql\Token\EndForToken;
use Kentoka\ExSql\Token\ForToken;
use Kentoka\ExSql\Token\PlainToken;
use Kentoka\ExSql\Token\VarToken;

class ExSql
{
    final public const T_PLAIN = 'plain';
    final public const T_VAR = 'var';
    final public const T_ELSE = 'else';
    final public const T_CASE = 'case';
    final public const T_ENDCASE = 'endcase';
    final public const T_FOR = 'for';
    final public const T_ENDFOR = 'endfor';

    public const TOKEN_PATTERNS = [
        ExSql::T_ELSE    => '(?<' . ExSql::T_ELSE    . '>%else)',
        ExSql::T_CASE    => '(?<' . ExSql::T_CASE    . '>%case :(?<' . ExSql::TKEY_CASE_VAR_NAME . '>[A-Za-z_][0-9A-Za-z_]*(?:\.[A-Za-z_][0-9A-Za-z_]*)*))',
        ExSql::T_ENDCASE => '(?<' . ExSql::T_ENDCASE . '>%endcase)',
        ExSql::T_FOR     => '(?<' . ExSql::T_FOR     . '>%for(?:\[(?<' . ExSql::TKEY_FOR_GLUE . '>[^\]]+)\])?(?: (?<' . ExSql::TKEY_FOR_SCOPE_NAME . '>[A-Za-z_][0-9A-Za-z_]*) of)? :(?<' . ExSql::TKEY_FOR_VAR_NAME . '>[A-Za-z_][0-9A-Za-z_]*(?:\.[A-Za-z_][0-9A-Za-z_]*)*))',
        ExSql::T_ENDFOR  => '(?<' . ExSql::T_ENDFOR  . '>%endfor)',
        ExSql::T_VAR     => '(?<' . ExSql::T_VAR     . '>:(?<' . ExSql::TKEY_VAR_NAME . '>[A-Za-z_][0-9A-Za-z_]*(?:\.[A-Za-z_][0-9A-Za-z_]*)*)(?:@(?<' . ExSql::TKEY_VAR_TYPE . '>[a-z]+))?)',
    ];

    final const TKEY_CASE_VAR_NAME = 'case_var_name';
    final const TKEY_FOR_GLUE = 'for_glue';
    final const TKEY_FOR_SCOPE_NAME = 'for_scope_name';
    final const TKEY_FOR_VAR_NAME = 'for_var_name';
    final const TKEY_VAR_NAME = 'var_name';
    final const TKEY_VAR_TYPE = 'var_type';

    /**
     *
     *
     * @param string $query
     * @param mixed[] $vars
     * @return string
     *
     * @phpstan-param array<non-empty-string,mixed> $vars
     */
    public static function resolve(string $query, array $vars): string
    {
        // TODO
        return $query;
    }

    /**
     *
     *
     * @param string $query
     * @return Token[]
     * @phpstan-return list<Token> todo
     */
    public static function tokenize(string $query): array
    {
        $matches = [];
        preg_match_all('/' . implode('|', static::TOKEN_PATTERNS) . '/', $query, $matches, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);

        $matches = array_map(
            fn(array $match) => array_filter(
                $match,
                fn($cap) => $cap[1] !== -1
            ),
            $matches
        );

        $tokens = [];
        $rest_query = $query;
        $processed_char_count = 0;
        foreach ($matches as $match) {
            $plain = substr($rest_query, 0, $match[0][1] - $processed_char_count);
            $char_count = strlen($plain) + strlen($match[0][0]);
            $rest_query = substr($rest_query, $char_count);

            $tokens[] = new PlainToken($plain, $processed_char_count);
            $tokens[] = self::makeToken($match);

            $processed_char_count += $char_count;
        }

        if ($rest_query !== '') {
            $tokens[] = new PlainToken($rest_query, $processed_char_count);
        }

        return $tokens;
    }

    /**
     * @param array $match
     * @return Token
     * @phpstan-param array<array{string,int}> $match
     */
    private static function makeToken(array $match): Token
    {
        return match (true) {
            isset($match[self::T_VAR]) => new VarToken(
                var_name: $match[self::TKEY_VAR_NAME][0],
                var_type: $match[self::TKEY_VAR_TYPE][0],
                offset: $match[0][1]
            ),
            isset($match[self::T_ELSE]) => new ElseToken(
                offset: $match[0][1]
            ),
            isset($match[self::T_CASE]) => new CaseToken(
                var_name: $match[self::TKEY_CASE_VAR_NAME][0],
                offset: $match[0][1]
            ),
            isset($match[self::T_ENDCASE]) => new EndCaseToken(
                offset: $match[0][1]
            ),
            isset($match[self::T_FOR]) => new ForToken(
                glue: $match[self::TKEY_FOR_GLUE][0] ?? null,
                scope_name: $match[self::TKEY_FOR_SCOPE_NAME][0] ?? null,
                var_name: $match[self::TKEY_FOR_VAR_NAME][0],
                offset: $match[0][1]
            ),
            isset($match[self::T_ENDFOR]) => new EndForToken(
                offset: $match[0][1]
            )
        };
    }
}
