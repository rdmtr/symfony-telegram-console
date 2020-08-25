<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Tests\Services;

use PHPUnit\Framework\TestCase;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Services\CommandRegistry;

/**
 * Class CommandRegistryTest
 */
final class CommandRegistryTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetSimpleCommandsTargets(): void
    {
        $registry = new CommandRegistry(
            [
                $this->createCommand('/simple'),
                $this->createCommand('/simpletwo'),
                $this->createCommand('Reply message one'),
                $this->createCommand('Reply message two'),
            ]
        );

        $this->assertSame(['/simple', '/simpletwo'], $registry->getSimpleCommandsTargets());
    }

    /**
     * @param string $targetText
     *
     * @return CommandInterface
     */
    private function createCommand(string $targetText): CommandInterface
    {
        $command = $this->createMock(CommandInterface::class);
        $command->method('getTargetText')->willReturn($targetText);

        return $command;
    }
}