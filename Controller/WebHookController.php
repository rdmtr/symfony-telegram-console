<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Controller;

use Rdmtr\TelegramConsole\Services\MessageHandler;
use Rdmtr\TelegramConsole\Api\Objects\Message;

/**
 * Class WebHookController
 */
class WebHookController
{
    /**
     * @param Message        $message
     * @param MessageHandler $replyProcessor
     */
    public function action(Message $message, MessageHandler $replyProcessor): void
    {
        $replyProcessor->handle($message);
    }
}