<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Command;

use Rdmtr\TelegramConsole\Services\BotConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigureCommand
 */
final class ConfigureCommand extends Command
{
    protected static $defaultName = 'telegram-console:configure';

    /**
     * @var BotConfigurator $botConfigurator
     */
    private $botConfigurator;

    /**
     * ConfigureCommand constructor.
     *
     * @param BotConfigurator $botConfigurator
     */
    public function __construct(BotConfigurator $botConfigurator)
    {
        parent::__construct(self::$defaultName);
        $this->botConfigurator = $botConfigurator;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->botConfigurator->configure();

        $output->writeln('Telegram bot configured successfully.');
    }
}