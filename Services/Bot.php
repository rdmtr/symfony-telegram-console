<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use Rdmtr\TelegramConsole\Api\Methods\SendMessage;
use Rdmtr\TelegramConsole\Api\Methods\SetMyCommands;
use Rdmtr\TelegramConsole\Api\Methods\SetWebhook;
use Rdmtr\TelegramConsole\Services\Api\Client;
use Rdmtr\TelegramConsole\Services\Api\ReplyMarkupGenerator;

/**
 * Class Bot
 */
class Bot
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ReplyMarkupGenerator
     */
    private $replyMarkupGenerator;

    /**
     * Bot constructor.
     *
     * @param Client               $client
     * @param ReplyMarkupGenerator $replyMarkupGenerator
     */
    public function __construct(Client $client, ReplyMarkupGenerator $replyMarkupGenerator)
    {
        $this->client = $client;
        $this->replyMarkupGenerator = $replyMarkupGenerator;
    }

    /**
     * @param int      $chatId
     * @param string   $text
     * @param int|null $replyToMessageId
     */
    public function say(int $chatId, string $text, int $replyToMessageId = null): void
    {
        do {
            $cutMessage = substr($text, 0, 4096); // telegram constraint
            $this->client->call(new SendMessage($chatId, htmlentities($cutMessage), $replyToMessageId));
            $text = substr($text, 4096);
        } while ($text && $text !== '');
    }

    /**
     * @param int      $chatId
     * @param string   $question
     * @param int|null $replyToMessageId
     */
    public function ask(int $chatId, string $question, int $replyToMessageId = null): void
    {
        $this->client->call(
            new SendMessage($chatId, $question, $replyToMessageId, $this->replyMarkupGenerator->forceReply())
        );
    }

    /**
     * @param int      $chatId
     * @param string   $question
     * @param array    $keyboard
     * @param int|null $replyToMessageId
     */
    public function askWithKeyboard(int $chatId, string $question, array $keyboard, int $replyToMessageId = null): void
    {
        $this->client->call(
            new SendMessage($chatId, $question, $replyToMessageId, $this->replyMarkupGenerator->keyboard($keyboard))
        );
    }
}