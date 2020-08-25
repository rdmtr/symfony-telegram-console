<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Services\Console;

/**
 * Class CommandSelectedCommand
 */
final class SelectCommand implements CommandInterface
{
    /**
     * @var string
     */
    private $question;

    /**
     * @var Bot
     */
    private $bot;

    /**
     * @var Console
     */
    private $console;

    /**
     * CommandSelectedCommand constructor.
     *
     * @param string  $question
     * @param Bot     $bot
     * @param Console $console
     */
    public function __construct(string $question, Bot $bot, Console $console)
    {
        $this->question = $question;
        $this->bot = $bot;
        $this->console = $console;
    }

    public function getTargetText(): string
    {
        return 'Select command';
    }

    public function execute(Message $message): void
    {
        $chatId = $message->getChat()->getId();
        $this->bot->say($chatId, $this->console->getCommandHelp($message->getText()), $message->getId());
        $this->bot->ask($chatId, $this->question, $message->getId());
    }
}