<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use DateTime;
use Generator;
use Psr\Log\LoggerInterface;
use Rdmtr\TelegramConsole\Api\Objects\Chat;
use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Api\Objects\User;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessageValueResolver
 */
final class MessageValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var OptionsResolver
     */
    private $optionResolver;
    /** @var LoggerInterface */
    private $logger;

    /**
     * MessageValueResolver constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->optionResolver = new OptionsResolver();
        $this->logger = $logger;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool|void
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return Message::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $update = json_decode($cont = $request->getContent(), true);
        $this->logger->error($cont);

        $message = is_array($update) ? ($update['message'] ?? false ) : null;
        if (!$message) {
            throw new BadRequestException('Request not matched to Telegram webhook request format.');
        }

        yield $this->createMessage($message);
    }

    /**
     * @param array $messageData
     *
     * @return Message
     */
    private function createMessage(array $messageData): Message
    {
        try {
            $this->optionResolver->setRequired(['message_id', 'from', 'chat', 'date', 'text'])->resolve($messageData);
        } catch (UndefinedOptionsException $e) {
        }

        $replyToMessage = null;
        if ($messageData['reply_to_message'] ?? false) {
            $replyToMessage = $this->createMessage($messageData['reply_to_message']);
        }

        $user = new User($messageData['from']['id'], $messageData['from']['username'], $messageData['from']['is_bot']);
        $chat = new Chat($messageData['chat']['id'], $messageData['chat']['type']);

        return new Message(
            $messageData['message_id'],
            (new DateTime())->setTimestamp($messageData['date']),
            $chat,
            $user,
            $messageData['text'], // messages like 'group_chat_created' need to be ignored instead of erroring
            $replyToMessage
        );
    }
}
