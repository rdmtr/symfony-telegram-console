<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Services\Console;
use Throwable;

final class RunCommand implements CommandInterface
{
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
     * @param Bot     $bot
     * @param Console $console
     */
    public function __construct(Bot $bot, Console $console)
    {
        $this->bot = $bot;
        $this->console = $console;
    }

    public function getTargetText(): string
    {
        return 'Provide command arguments|options or type nothing if they are not applicable';
    }

    public function execute(Message $message): void
    {
        $commandInput = $message->getReplyToMessage()->getText().' '.$message->getText();
        $chatId = $message->getChat()->getId();
        $this->bot->say($chatId, sprintf('Running "%s" ...', $commandInput), $replyToId = $message->getId());

        try {
            $output = $this->console->runCommand($commandInput);
            $this->bot->say(
                $chatId,
                sprintf('Command "%s" successfully executed. Output: "%s"', $commandInput, $output),
                $replyToId
            );
        } catch (Throwable $t) {
            $this->bot->say($chatId, sprintf('Some error occurred: "%s".', $t->getMessage()), $replyToId);
        }
    }
}