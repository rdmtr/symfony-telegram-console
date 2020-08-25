<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Services\Console;

/**
 * Class SelectNamespaceCommand
 */
final class SelectNamespaceCommand implements CommandInterface
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
     * SelectNamespaceCommand constructor.
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
        return 'Select command namespace';
    }

    public function execute(Message $message): void
    {
        $this->bot->askWithKeyboard(
            $message->getChat()->getId(),
            $this->question,
            array_keys($this->console->getCommandList($message->getText())),
            $message->getId()
        );
    }
}