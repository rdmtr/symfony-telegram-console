<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api\Objects;

/**
 * Class User
 */
final class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var bool
     */
    private $isBot;

    /**
     * User constructor.
     *
     * @param int    $id
     * @param string $username
     * @param bool   $isBot
     */
    public function __construct(int $id, string $username, bool $isBot)
    {
        $this->id = $id;
        $this->username = $username;
        $this->isBot = $isBot;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->isBot;
    }
}