<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Helper\DescriptorHelper;
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
     * @var array
     */
    private $excludedNamespaces;

    /**
     * ConsoleCommandManager constructor.
     *
     * @param KernelInterface $kernel
     * @param array           $excludedNamespaces
     */
    public function __construct(KernelInterface $kernel, array $excludedNamespaces)
    {
        $this->application = new Application($kernel);
        $this->excludedNamespaces = $excludedNamespaces;

        $this->application->setCatchExceptions(false);
        $this->application->setAutoExit(false);
    }

    /**
     * @return array
     */
    public function getNamespaces(): array
    {
        foreach ($namespaces = $this->application->getNamespaces() as $i => $namespace) {
            foreach ($this->excludedNamespaces as $excludedNamespace) {
                if (0 === strpos($namespace, $excludedNamespace)) {
                    unset($namespaces[$i]);
                }
            }
        }

        return $namespaces;
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
        $helper = new DescriptorHelper();
        $output = new BufferedOutput();
        $helper->describe(
            $output,
            $this->application->get($command),
            ['format' => 'txt']
        );

        return $output->fetch();
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function runCommand(string $input): string
    {
        $input = preg_replace('/\s+/', ' ', $input);
        $input = new ArgvInput(explode(' ', 'bin/console '.$input));
        $output = new BufferedOutput();

        $this->application->run($input, $output);

        return $output->fetch();
    }
}