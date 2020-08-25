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
     * @param string $filledMessage
     * @param string $patternMessage
     *
     * @return bool
     */
    public function isMatched(string $filledMessage, string $patternMessage): bool
    {
        if ($filledMessage === $patternMessage) {
            return true;
        }

        return [] !== $this->getPlaceholders($filledMessage, $patternMessage);
    }

    /**
     * @param string $filledMessage
     * @param string $patternMessage
     *
     * @return array
     */
    public function getPlaceholders(string $filledMessage, string $patternMessage): array
    {
        $parameterNames = [];
        if (!preg_match_all(self::PLACEHOLDER_REGEXP, $patternMessage, $parameterNames)) {
            return [];
        }

        $patternMessage = str_replace(self::REGEXP_SYMBOLS, '', $patternMessage);
        $filledMessage = str_replace(self::REGEXP_SYMBOLS, '', $filledMessage);
        $regexp = preg_replace(self::PLACEHOLDER_REGEXP, '([a-zA-Z0-9_:-]+)', $patternMessage);

        $values = [];
        if (!preg_match('/'.$regexp.'/', $filledMessage, $values)) {
            return [];
        }

        array_shift($values);

        return array_combine($parameterNames[1] ?? [], $values);
    }
}