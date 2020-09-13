<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

/**
 * Class Matcher
 */
final class Matcher
{
    private const PLACEHOLDER_REGEXP = '/\{(\w+)\}/';
    private const REGEXP_SYMBOLS = ['"', '/', '|', '^', '?', '[', ']'];

    /**
     * @var bool[]
     */
    private $cache = [];

    /**
     * @param string $text
     * @param string $pattern
     *
     * @return bool
     */
    public static function isMatched(string $text, string $pattern): bool
    {
        if ($text === $pattern) {
            return true;
        }

        $cacheItem = $text . '|' . $pattern;
        if (array_key_exists($cacheItem, self::$cache)) {
            return self::$cache[$cacheItem];
        }

        return self::$cache[$cacheItem] = [] !== self::getPlaceholders($text, $pattern);
    }

    /**
     * @param string $text
     * @param string $pattern
     *
     * @return array
     */
    public static function getPlaceholders(string $text, string $pattern): array
    {
        $parameterNames = [];
        if ($text === $pattern || !preg_match_all(self::PLACEHOLDER_REGEXP, $pattern, $parameterNames)) {
            return [];
        }

        $pattern = str_replace(self::REGEXP_SYMBOLS, '', $pattern);
        $text = str_replace(self::REGEXP_SYMBOLS, '', $text);
        $regexp = preg_replace(self::PLACEHOLDER_REGEXP, '([a-zA-Z0-9_:-]+)', $pattern);

        $values = [];
        if (!preg_match('/'.$regexp.'/', $text, $values)) {
            return [];
        }

        array_shift($values);

        return array_combine($parameterNames[1] ?? [], $values);
    }
}
