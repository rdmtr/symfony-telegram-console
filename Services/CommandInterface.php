<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use Rdmtr\TelegramConsole\Api\Objects\Message;

/**
 * Interface CommandInterface
 */
interface CommandInterface
{
    /**
     * @return string
     */
    public function getTargetText(): string;

    /**
     * @param Message $message
     */
    public function execute(Message $message): void;
}