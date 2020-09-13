<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api\Objects;

use DateTime;

/**
 * Class Message
 */
final class Message
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var Chat
     */
    private $chat;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $text;

    /**
     * @var self|null
     */
    private $replyToMessage;

    /**
     * Message constructor.
     *
     * @param int          $id
     * @param DateTime     $date
     * @param Chat         $chat
     * @param User         $user
     * @param string       $text
     * @param Message|null $replyToMessage
     */
    public function __construct(int $id, DateTime $date, Chat $chat, User $user, string $text, self $replyToMessage = null)
    {
        $this->id = $id;
        $this->date = $date;
        $this->chat = $chat;
        $this->user = $user;
        $this->text = $text;
        $this->replyToMessage = $replyToMessage;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return $this
     */
    public function getReplyToMessage(): self
    {
        return $this->replyToMessage;
    }

    /**
     * @return string
     */
    public function getTargetText(): string
    {
        if (!$this->chat->isPrivate()) {
            $replyToMessageText = $this->replyToMessage ? $this->replyToMessage->getText() : '';

            return $this->isCommand() ? $this->text : $replyToMessageText;
        }

        return $this->text;
    }

    /**
     * @return bool
     */
    public function isCommand(): bool
    {
        return 0 === strpos($this->text, '/');
    }

    /**
     * @return bool
     */
    public function isBotMessageReply(): bool
    {
        if (null === $this->replyToMessage) {
            return false;
        }

        return true === $this->replyToMessage->getUser()->isBot();
    }
}
