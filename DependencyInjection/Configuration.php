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
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('telegram_console');
        $rootNode
            ->beforeNormalization()
                ->always()
                ->then(
                    static function ($val) {
                        if ([] === $val || ($val['webhook_url'] ?? false)) {
                            return $val;
                        }
                        $val['webhook_url'] = '/'.$val['token'];

                        return $val;
                    }
                )
            ->end()
            ->children()
                ->scalarNode('token')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('webhook_url')->defaultNull()->end()
                ->arrayNode('privacy')->isRequired()
                    ->validate()
                        ->ifEmpty()
                        ->thenInvalid('Requires at least one privacy option filled: "chat_id" or "user_list".')
                    ->end()
                    ->children()
                        ->scalarNode('chat_id')->validate()->ifEmpty()->thenUnset()->end()->end()
                        ->scalarNode('users')->validate()->ifEmpty()->thenUnset()->end()->end()
                    ->end()
                ->end() // privacy
            ->end();

        return $treeBuilder;
    }
}