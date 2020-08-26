<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use LogicException;
use Rdmtr\TelegramConsole\Api\Objects\Message;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

/**
 * Class MessageProcessor
 */
final class MessageHandler
{
    /**
     * @var Bot
     */
    private $bot;

    /**
     * @var CommandRegistry
     */
    private $commands;

    /**
     * @var array
     */
    private $acceptedUsers;

    /**
     * @var int|null
     */
    private $acceptedChat;

    /**
     * ReplyProcessor constructor.
     *
     * @param Bot             $bot
     * @param CommandRegistry $commands
     * @param array           $acceptedUsers
     * @param int|null        $acceptedChat
     */
    public function __construct(Bot $bot, CommandRegistry $commands, array $acceptedUsers, ?int $acceptedChat)
    {
        $this->bot = $bot;
        $this->commands = $commands;
        $this->acceptedUsers = $acceptedUsers;
        $this->acceptedChat = $acceptedChat;
    }

    /**
     * @param Message $message
     *
     * @throws Throwable
     */
    public function handle(Message $message): void
    {
        try {
            $isGranted = in_array($message->getUser()->getUsername(), $this->acceptedUsers, true)
                || $message->getChat()->getId() === $this->acceptedChat;

            if (!$isGranted) {
                throw new AccessDeniedHttpException(
                    'You has not access to bot. Please edit bundle configs to use me.'
                );
            }

            if (!$message->getChat()->isPrivate() && !$message->isBotMessageReply() && !$message->isCommand()) {
                throw new LogicException(
                    'Only bot replies and bot commands supported. Check privacy mode.'
                );
            }

            if (!$command = $this->commands->get($message->getCommandTargetText())) {
                throw new LogicException(
                    sprintf('Unsupported command for message with text "%s".', $message->getText())
                );
            }

            $command->execute($message);
        } catch (Throwable $t) {
            $this->bot->say($message->getChat()->getId(), $t->getMessage(), $message->getId());
        }
    }
}