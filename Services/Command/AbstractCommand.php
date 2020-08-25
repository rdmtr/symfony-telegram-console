<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\CommandInterface;

/**
 * Class AbstractSimpleCommand
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @param string $message
     *
     * @return bool
     */
    public function isMatches(string $message): bool
    {
        return $this->getTargetText() === $message;
    }

    /**
     * @inheritDoc
     */
    abstract public function getTargetText(): string;

    /**
     * @inheritDoc
     */
    abstract public function execute(Message $message): void;
}