<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api\Objects;

use InvalidArgumentException;

/**
 * Class Chat
 */
final class Chat
{
    private const TYPE_PRIVATE = 'private';
    private const TYPE_GROUP = 'group';
    private const TYPE_SUPERGROUP = 'supergroup';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * Chat constructor.
     *
     * @param int $id
     * @param string $type
     */
    public function __construct(int $id, string $type)
    {
        if (!in_array($type, [self::TYPE_GROUP, self::TYPE_PRIVATE, self::TYPE_SUPERGROUP])) {
            throw new InvalidArgumentException('Type must be "private" or "group".');
        }

        $this->id = $id;
        $this->type = $type;
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
    public function getType(): string
    {
        return $this->type;
    }
}