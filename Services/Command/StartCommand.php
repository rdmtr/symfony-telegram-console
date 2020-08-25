<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\Console;

final class StartCommand implements CommandInterface
{
    /**
     * @var Bot
     */
    private $bot;

    /**
     * @var string
     */
    private $question;

    /**
     * @var Console
     */
    private $consoleManager;

    /**
     * StartCommand constructor.
     *
     * @param string  $question
     * @param Bot     $bot
     * @param Console $consoleManager
     */
    public function __construct(string $question, Bot $bot, Console $consoleManager)
    {
        $this->bot = $bot;
        $this->question = $question;
        $this->consoleManager = $consoleManager;
    }

    /**
     * @return string
     */
    public function getTargetText(): string
    {
        return '/start';
    }

    /**
     * @param Message $message
     */
    public function execute(Message $message): void
    {
        $this->bot->askWithKeyboard($message->getChat()->getId(), $this->question, $this->consoleManager->getNamespaces());
    }
}