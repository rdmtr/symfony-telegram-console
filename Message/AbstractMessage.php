<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Message;

use InvalidArgumentException;
use Rdmtr\TelegramConsole\Api\Objects\Message as ApiMessage;
use Rdmtr\TelegramConsole\Exception\InvalidMessageException;
use Rdmtr\TelegramConsole\Services\Matcher;

abstract class AbstractMessage
{
    /** @var string */
    protected $text;

    /** @var array */
    protected $placeholders;

    /**
     * @param string $text
     */
    public function __construct(string $text)
    {
        if (!static::isMatched($text)) {
            throw new InvalidMessageException($text);
        }

        $this->text = $text;
        $this->placeholders = Matcher::getPlaceholders($text, static::getPattern());
    }

    public static function isMatched(string $text): bool
    {
        return Matcher::isMatched($text, static::getPattern());
    }

    public static function isCommand(): bool
    {
        return 0 === strpos(static::getPattern(), '/');
    }

    /**
     * For simple commands or messages it is text as it is (e.g. `/help` or 'Do anything').
     * If message can contains dynamic values but has common pattern you must provide pattern with placeholders
     * (e.g. `My name is {name}`).
     *
     * @return string
     */
    abstract protected static function getPattern(): string;
}
