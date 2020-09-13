<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Message;

final class Help extends AbstractMessage
{
    public function getCommand(): string
    {
        return $this->placeholders['command'];
    }

    protected static function getPattern(): string
    {
        return '/help';
    }
}
