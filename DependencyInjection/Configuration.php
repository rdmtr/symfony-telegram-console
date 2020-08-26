<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Namespaces which commands can crush bot
     */
    private const EXCLUDED_NAMESPACES = ['cache'];

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('telegram_console');
        $rootNode
            ->beforeNormalization()
                ->always(
                    static function ($val) {
                        if (!array_key_exists('webhook_url', $val)) {
                            $val['webhook_url'] = '/'.$val['token'];
                        }
                        if (!array_key_exists('excluded_namespaces', $val)) {
                            $val['excluded_namespaces'] = [];
                        }
                        $val['excluded_namespaces'] = array_merge($val['excluded_namespaces'], self::EXCLUDED_NAMESPACES);

                        return $val;
                    }
                )
            ->end()
            ->children()
                ->scalarNode('token')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('webhook_url')->end()
                ->arrayNode('privacy')->isRequired()
                    ->validate()
                        ->ifEmpty()
                        ->thenInvalid('Requires at least one privacy option filled: "chat_id" or "user_list".')
                    ->end()
                    ->children()
                        ->integerNode('chat_id')->validate()->ifEmpty()->thenUnset()->end()->end()
                        ->arrayNode('users')
                            ->validate()->ifEmpty()->thenUnset()->end()
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end() // privacy
                ->arrayNode('excluded_namespaces')
                    ->defaultValue([])
                    ->scalarPrototype()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}