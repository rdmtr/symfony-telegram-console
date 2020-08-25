<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;

/**
 * Class HelpCommand
 */
final class HelpCommand implements CommandInterface
{
    /**
     * @var Bot
     */
    private $bot;

    /**
     * HelpCommand constructor.
     *
     * @param Bot $bot
     */
    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * @return string
     */
    public function getTargetText(): string
    {
        return '/help';
    }

    /**
     * @param Message $message
     */
    public function execute(Message $message): void
    {
        $this->bot->say(
            $message->getChat()->getId(),
            <<<'EOF'
I am Telegram Console Bot.
 
I can provide interface for symfony application console commands running.

You can start me by sending me /start message and then choose console command which you want to run.

For developers: <a href="https://github.com/rdmtr/symfony-telegram-console">rdmtr/symfony-telegram-console</a>.
EOF
            ,
            $message->getId()
        );
    }
}