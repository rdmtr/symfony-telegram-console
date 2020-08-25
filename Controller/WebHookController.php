<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Controller;

use Psr\Log\LoggerInterface;
use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Services\MessageHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class WebHookController
 */
class WebHookController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * WebHookController constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Message        $message
     * @param MessageHandler $replyProcessor
     *
     * @return Response
     */
    public function action(Message $message, MessageHandler $replyProcessor): Response
    {
        try {
            $replyProcessor->handle($message);
        } catch (Throwable $t) {
            // applicable for webhook to avoid repeated error requests
            $this->logger->error(
                sprintf(
                    'Telegram Console Error %s: "%s" at %s:%s',
                    get_class($t),
                    $t->getMessage(),
                    $t->getFile(),
                    $t->getLine()
                )
            );
        }

        return Response::create('');
    }
}