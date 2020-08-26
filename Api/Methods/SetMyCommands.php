<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api\Methods;

use Rdmtr\TelegramConsole\Api\MethodInterface;

/**
 * Class SetMyCommands
 */
final class SetMyCommands implements MethodInterface
{
    /**
     * @var array
     */
    private $commands;

    /**
     * SetMyCommands constructor.
     *
     * @param array<string, string> $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public function getName(): string
    {
        return 'setMyCommands';
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $commands = [];
        foreach ($this->commands as $name => $description) {
            $commands[] = is_int($name)
                ? ['command' => $description, 'description' => '---']
                : ['command' => $name, 'description' => $description];
        }

        return ['commands' => $commands];
    }
}