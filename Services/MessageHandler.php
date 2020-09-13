<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use LogicException;
use Rdmtr\TelegramConsole\Api\Objects\Message as ApiMessage;
use Rdmtr\TelegramConsole\Services\Message\MessageFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
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
    /** @var MessageBusInterface */
    private $bus;
    /** @var MessageFactory */
    private $messageResolver;

    /**
     * ReplyProcessor constructor.
     *
     * @param Bot $bot
     * @param MessageFactory $messageResolver
     * @param MessageBusInterface $bus
     * @param array $acceptedUsers
     * @param int|null $acceptedChat
     */
    public function __construct(Bot $bot, MessageFactory $messageResolver, /*MessageBusInterface $bus, */array $acceptedUsers, ?int $acceptedChat)
    {
        $this->bot = $bot;
       // $this->bus = $bus;
        $this->acceptedUsers = $acceptedUsers;
        $this->acceptedChat = $acceptedChat;
        $this->messageResolver = $messageResolver;
    }

    /**
     * @param ApiMessage $message
     *
     * @throws Throwable
     */
    public function handle(ApiMessage $message): void
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

            if (!$command = $this->commands->get($message->getTargetText())) {
                throw new LogicException(
                    sprintf('Unsupported command for message with text "%s".', $message->getText())
                );
            }

            $this->bus->dispatch($this->messageResolver->create($message->getText()));
        } catch (Throwable $t) {
            $this->bot->say($message->getChat()->getId(), $t->getMessage(), $message->getId());
        }
    }
}
