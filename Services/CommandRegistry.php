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
     * @return CommandInterface|null
     */
    public function get(string $targetText): ?CommandInterface
    {
        foreach ($this->commands as $command) {
            if ($command->isMatches($targetText)) {
                return $command;
            }
        }

        return null;
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