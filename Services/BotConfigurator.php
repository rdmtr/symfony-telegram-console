<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services;

use Rdmtr\TelegramConsole\Api\Methods\SetMyCommands;
use Rdmtr\TelegramConsole\Api\Methods\SetWebhook;
use Rdmtr\TelegramConsole\Services\Api\Client;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BotConfigurator
 */
class BotConfigurator
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var CommandRegistry
     */
    private $commands;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * BotConfigurator constructor.
     *
     * @param Client                $client
     * @param CommandRegistry       $commands
     * @param UrlGeneratorInterface $router
     */
    public function __construct(Client $client, CommandRegistry $commands, UrlGeneratorInterface $router)
    {
        $this->client = $client;
        $this->commands = $commands;
        $this->router = $router;
    }

    /**
     * @return void
     */
    public function configure(): void
    {
        $url = $this->router->generate('telegram_console.webhook_action', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->client->call(new SetWebhook($url));
        $this->client->call(new SetMyCommands($this->commands->getSimpleCommandsTargets()));
    }
}