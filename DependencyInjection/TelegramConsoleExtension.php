<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class TelegramConsoleExtension
 */
class TelegramConsoleExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('telegram_console.token', $config['token']);
        $container->setParameter('telegram_console.webhook_url', $config['webhook_url']);
        $container->setParameter('telegram_console.excluded_namespaces', $config['excluded_namespaces']);
        $container->setParameter('telegram_console.accepted_users', $config['privacy']['users'] ?? []);
        $container->setParameter('telegram_console.accepted_chat', $config['privacy']['chat_id'] ?? null);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}