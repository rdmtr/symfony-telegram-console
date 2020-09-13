<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Message;

use Rdmtr\TelegramConsole\Exception\InvalidMessageException;
use Rdmtr\TelegramConsole\Message\AbstractMessage;

final class MessageFactory
{
    /**
     * @var AbstractMessage[]
     */
    private $messages;

    /**
     * @param AbstractMessage[] $messages
     */
    public function __construct(iterable $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @param string $text
     * @return AbstractMessage
     */
    public function create(string $text): AbstractMessage
    {
        foreach ($this->messages as $message) {
            if ($message::isMatched($text)) {
                return new $message($text);
            }
        }

        throw new InvalidMessageException($text);
    }
}
