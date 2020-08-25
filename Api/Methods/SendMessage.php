<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api\Methods;

use Rdmtr\TelegramConsole\Api\MethodInterface;
use Rdmtr\TelegramConsole\Api\Objects\ReplyKeyboardMarkup;

/**
 * Class Message
 */
final class SendMessage implements MethodInterface
{
    public const PARSE_MODE_HTML = 'HTML';
    public const PARSE_MODE_MARKDOWN = 'MARKDOWN';

    /**
     * @var int
     */
    private $chatId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $parseMode;

    /**
     * @var array|null
     */
    private $replyMarkup;

    /**
     * @var int|null
     */
    private $replyToMessageId;

    /**
     * Message constructor.
     *
     * @param int         $chatId
     * @param string      $text
     * @param int|null    $replyToMessageId
     * @param array|null  $replyMarkup
     * @param string|null $parseMode
     */
    public function __construct(
        int $chatId,
        string $text,
        int $replyToMessageId = null,
        array $replyMarkup = null,
        string $parseMode = self::PARSE_MODE_HTML
    ) {

        $this->chatId = $chatId;
        $this->text = $text;
        $this->parseMode = $parseMode;
        $this->replyMarkup = $replyMarkup;
        $this->replyToMessageId = $replyToMessageId;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $sendMessage = [
            'chat_id'      => $this->chatId,
            'parse_mode'   => $this->parseMode,
            'text'         => $this->text,
        ];

        if ($this->replyMarkup) {
            $sendMessage['reply_markup'] = $this->replyMarkup;
        }

        if ($this->replyToMessageId) {
            $sendMessage['reply_to_message_id'] = $this->replyToMessageId;
        }

        return $sendMessage;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'sendMessage';
    }
}
