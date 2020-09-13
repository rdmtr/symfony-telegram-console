<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Exception;

use InvalidArgumentException;
use Throwable;

class InvalidMessageException extends InvalidArgumentException
{
    public function __construct(string $messageText)
    {
        parent::__construct(sprintf('Invalid message text: "%s".', $messageText));
    }
}
