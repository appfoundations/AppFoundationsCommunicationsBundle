<?php

namespace AppFoundations\CommunicationsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AppFoundationsCommunicationsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (isset($config['sms'])) {
            $container->setParameter('app_foundations_communications.sms',array(
                'provider' => $config['sms']['provider'],
                'mode' => $config['sms']['mode'],
                'persistence' => $config['sms']['persistence'],
                'minhour' => $config['sms']['minhour'],
                'dynmark' => array (
                    'username' => $config['sms']['dynmark']['username'],
                    'password' => $config['sms']['dynmark']['password'],
                ),
                'comapi' => array (
                    'space' => $config['sms']['comapi']['space'],
                    'token' => $config['sms']['comapi']['token'],
                    'sender' => $config['sms']['comapi']['sender']
                ),
            ));
        } else {
            $container->setParameter('app_foundations_communications.sms',array());
        }

        if (isset($config['email'])){
            $emailParams = array(
                'provider' => $config['email']['provider'],
                'sendgridKey' => $config['email']['sendgrid']['key'],
                'sendgridRegion' => $config['email']['sendgrid']['region'],
            );

            $container->setParameter('app_foundations_communications.email',$emailParams);
        }else{
            $container->setParameter('app_foundations_communications.email',array(
                'provider' => 'dummy',
                'sendgridKey' => 'no_key'
            ));
        }
    }
}
