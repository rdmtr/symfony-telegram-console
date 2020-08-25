<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

/**
 * Class CommandRegistry
 */
final class CommandRegistry
{
    /**
     * @var CommandInterface[]
     */
    private $commands;

    /**
     * CommandRegistry constructor.
     *
     * @param CommandInterface[] $commands
     */
    public function __construct(iterable $commands)
    {
        foreach ($commands as $command) {
            $this->commands[$command->getTargetText()] = $command;
        }
    }

    /**
     * @param string $targetText
     *
     * @return bool
     */
    public function has(string $targetText): bool
    {
        return array_key_exists($targetText, $this->commands);
    }

    /**
     * @param string $targetText
     *
     * @return CommandInterface
     */
    public function get(string $targetText): CommandInterface
    {
        return $this->commands[$targetText];
    }

    /**
     * @return string[]
     */
    public function getSimpleCommandsTargets(): array
    {
        $texts = [];
        foreach ($this->commands as $targetText => $command) {
            if (0 === strpos($targetText, '/')) {
                $texts[] = $targetText;
            }
        }

        return $texts;
    }
}