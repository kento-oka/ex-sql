<?php

declare(strict_types=1);

namespace Kentoka\ExSql;

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
        ExSql::T_CASE    => '(?<' . ExSql::T_CASE    . '>%case :(?<case_var_name>[A-Za-z_][0-9A-Za-z_]*(?:\.[A-Za-z_][0-9A-Za-z_]*)*))',
        ExSql::T_ENDCASE => '(?<' . ExSql::T_ENDCASE . '>%endcase)',
        ExSql::T_FOR     => '(?<' . ExSql::T_FOR     . '>%for(?:\[(?<for_glue>[^\]]+)\])?(?: (?<for_scope_name>[A-Za-z_][0-9A-Za-z_]*) of)? :(?<for_var_name>[A-Za-z_][0-9A-Za-z_]*(?:\.[A-Za-z_][0-9A-Za-z_]*)*))',
        ExSql::T_ENDFOR  => '(?<' . ExSql::T_ENDFOR  . '>%endfor)',
        ExSql::T_VAR     => '(?<' . ExSql::T_VAR     . '>:(?<var_name>[A-Za-z_][0-9A-Za-z_]*(?:\.[A-Za-z_][0-9A-Za-z_]*)*)(?:@(?<var_type>[a-z]+))?)',
    ];

    final const TKEY_PLAIN = 'plain';

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
     * @return array[]
     * @phpstan-return list<array<mixed>> todo
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
            $processed_char_count += $char_count;

            $tokens[] = ['type' => self::T_PLAIN, self::TKEY_PLAIN => $plain];
            $tokens[] = ['type' => 'token', self::TKEY_PLAIN => $match[0][0]];
        }

        if ($rest_query !== '') {
            $tokens[] = ['type' => self::T_PLAIN, self::TKEY_PLAIN => $rest_query];
        }

        return $tokens;
    }
}
