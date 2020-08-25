<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
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
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
        $this->application->setCatchExceptions(false);
    }

    /**
     * @return array
     */
    public function getNamespaces(): array
    {
        return $this->application->getNamespaces();
    }

    /**
     * @param string $namespace
     *
     * @return array
     */
    public function getCommandList(string $namespace): array
    {
        $commands = [];
        foreach ($this->application->all($namespace) as $command) {
            $commands[$command->getName()] = $command->getDescription();
        }

        return $commands;
    }

    /**
     * @param string $command
     *
     * @return string
     */
    public function getCommandHelp(string $command): string
    {
        return $this->application->get($command)->getHelp();
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function runCommand(string $input): string
    {
        $input = new ArgvInput(explode(' ', preg_replace('/\s+/', ' ', $input)));
        $output = new BufferedOutput();
        $this->application->run($input, $output);

        return $output->fetch();
    }
}