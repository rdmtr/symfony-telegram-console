<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ConsoleCommandManager
 */
final class Console
{
    /**
     * @var Application
     */
    private $application;

    /**
     * ConsoleCommandManager constructor.
     *
     * @param Application $application
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
        $this->application->setCatchExceptions(false);
    }

    public function getNamespaces(): array
    {
        return $this->application->getNamespaces();
    }

    public function getCommandList(string $namespace): array
    {
        $commands = [];
        foreach ($this->application->all($namespace) as $command) {
            $commands[$command->getName()] = $command->getDescription();
        }

        return $commands;
    }

    public function getCommandHelp(string $command)
    {
        return $this->application->get($command)->getHelp();
    }

    public function runCommand(string $input)
    {
        $input = new ArgvInput(explode(' ', preg_replace('/\s+/', ' ', $input)));
        $output = new BufferedOutput();
        $this->application->run($input, $output);

        return $output->fetch();
    }
}