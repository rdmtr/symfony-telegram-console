<?php

declare(strict_types=1);

use Rdmtr\TelegramConsole\Message\Help;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class HelpHandler implements MessageHandlerInterface
{
    public function __invoke(Help $message)
    {
        // TODO: Implement __invoke() method.
    }
}
