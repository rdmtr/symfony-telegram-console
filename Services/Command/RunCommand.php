<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Command;

use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Services\Console;
use Rdmtr\TelegramConsole\Services\Matcher;
use Throwable;

/**
 * Class RunCommand
 */
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
     * @var Matcher
     */
    private $matcher;

    /**
     * SelectNamespaceCommand constructor.
     *
     * @param Bot     $bot
     * @param Console $console
     * @param Matcher $matcher
     */
    public function __construct(Bot $bot, Console $console, Matcher $matcher)
    {
        $this->bot = $bot;
        $this->console = $console;
        $this->matcher = $matcher;
    }

    /**
     * @param string $message
     *
     * @return bool
     */
    public function isMatches(string $message): bool
    {
        return $this->matcher->isMatched($message, $this->getTargetText());
    }

    /**
     * @return string
     */
    public function getTargetText(): string
    {
        return 'Provide arguments and options for command {command} or type "-" if they are not applicable. Use code formatting like: ```--parameter test```';
    }

    /**
     * @param Message $message
     */
    public function execute(Message $message): void
    {
        $replyToMessage = $message->getReplyToMessage();
        $options = '-' === $message->getText() ? '' : $message->getText();

        $command = $this->matcher->getPlaceholders($replyToMessage->getText(), $this->getTargetText())['command'];
        $commandInput = trim($command.' '.$options);

        $chatId = $message->getChat()->getId();

        // TODO ask with performing
        $this->bot->say($chatId, sprintf('Your command "%s".', $commandInput), $replyToId = $message->getId());
        $this->bot->say($chatId, 'Running ...', $replyToId);

        $output = $this->console->runCommand($commandInput);
        $this->bot->say(
            $chatId,
            sprintf('Command "%s" successfully executed. Output: "%s"', $commandInput, $output),
            $replyToId
        );
    }
}