<?php

namespace AppFoundations\CommunicationsBundle\DependencyInjection;

use AppFoundations\CommunicationsBundle\Service\HermesEmailService;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use AppFoundations\CommunicationsBundle\Service\HermesSmsService;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app_foundations_communications');

        $rootNode
            ->fixXmlConfig('dynmark')
            ->children()
                ->arrayNode('sms')

                    ->children()
                        ->arrayNode('dynmark')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('username')->defaultValue(null)->end()
                                ->scalarNode('password')->defaultValue(null)->end()
                            ->end()
                        ->end()
                        ->integerNode('minhour')
                            ->defaultValue( 7 )
                        ->end()
                        ->enumNode('provider')
                            ->values(array( HermesSmsService::PROVIDER_TXTLOCAL,HermesSmsService::PROVIDER_DYNMARK,HermesSmsService::PROVIDER_NONE))
                            ->defaultValue( HermesSmsService::PROVIDER_NONE )
                        ->end()
                        ->enumNode('mode')
                            ->values(array( HermesSmsService::MODE_SYNC, HermesSmsService::MODE_ASYNC))
                            ->defaultValue( HermesSmsService::MODE_SYNC)
                        ->end()
                        ->enumNode('persistence')
                            ->values(array( HermesSmsService::PERSISTENCE_FILE, HermesSmsService::PERSISTENCE_DATABASE, HermesSmsService::PERSISTENCE_NONE))
                            ->defaultValue( HermesSmsService::PERSISTENCE_NONE)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('email')
                    ->children()
                        ->enumNode('provider')
                            ->values(array(HermesEmailService::PROVIDER_DUMMY,HermesEmailService::PROVIDER_SENDGRID))
                            ->defaultValue(HermesEmailService::PROVIDER_DUMMY)
                        ->end()
                        ->arrayNode('sendgrid')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('key')->defaultValue('no_key')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
