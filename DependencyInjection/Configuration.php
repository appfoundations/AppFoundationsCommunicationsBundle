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
     *
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('app_foundations_communications');
        $root = $treeBuilder->getRootNode();

        $rootNode->fixXmlConfig('dynmark')
            ->children()
            ->arrayNode('sms')
            ->
        children()
            ->arrayNode('dynmark')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('username')
            ->defaultValue(null)
            ->end()
            ->scalarNode('password')
            ->defaultValue(null)
            ->end()
            ->end()
            ->end()
            ->arrayNode('comapi')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('space')
            ->defaultValue(null)
            ->end()
            ->scalarNode('token')
            ->defaultValue(null)
            ->end()
            ->scalarNode('sender')
            ->defaultValue(null)
            ->end()
            ->end()
            ->end()
            ->integerNode('minhour')
            ->defaultValue(7)
            ->end()
            ->scalarNode('provider')
            ->defaultValue(HermesSmsService::PROVIDER_NONE)
            ->end()
            ->scalarNode('mode')
            ->defaultValue(HermesSmsService::MODE_SYNC)
            ->end()
            ->scalarNode('persistence')
            ->defaultValue(HermesSmsService::PERSISTENCE_NONE)
            ->end()
            ->end()
            ->end()
            ->arrayNode('email')
            ->children()
            ->scalarNode('provider')
            ->defaultValue(HermesEmailService::PROVIDER_DUMMY)
            ->end()
            ->arrayNode('sendgrid')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('key')
            ->defaultValue('no_key')
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
