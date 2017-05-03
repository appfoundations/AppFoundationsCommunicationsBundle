<?php

namespace AppFoundations\CommunicationsBundle\Service;

use Monolog\Logger;
use Doctrine\Common\Persistence\ManagerRegistry;
use AppFoundations\CommunicationsBundle\SmsProvider\ISmsProvider;
use AppFoundations\CommunicationsBundle\SmsProvider\DynmarkSmsProvider;

class HermesSmsService
{

    const PROVIDER_TXTLOCAL = 'txtlocal';
    const PROVIDER_DYNMARK  = 'dynmark';
    const PROVIDER_NONE     = 'none';

    const MODE_SYNC     = 'sync';
    const MODE_ASYNC    = 'async';

    const PERSISTENCE_FILE      = 'file';
    const PERSISTENCE_DATABASE  = 'database';
    const PERSISTENCE_NONE      = 'none';

    /**
     * @var ISmsProvider
     */
    private $provider;

    private $configuration;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * HermesService constructor.
     * @param string $providerName
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param Logger $logger
     */
    public function __construct($configuration, ManagerRegistry $managerRegistry, Logger $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->logger = $logger->withName("Logicc/HermesSmsService");
        $this->configuration = $configuration;

        switch ($configuration['provider']) {
            case 'dynmark':
                $this->provider = new DynmarkSmsProvider($configuration);
                break;
            case 'none':
                $this->provider = NULL;
                break;
            default:
                throw new \Exception('Unknown provider: ' . $providerName);
        }

        if ($this->configuration['mode'] == self::MODE_ASYNC) {
            throw new \Exception('Async mode not implemented');
        }

        if ($this->configuration['persistence'] != self::PERSISTENCE_NONE) {
            throw new \Exception('Persistence not implemented');
        }
    }


    /**
     * Send a mail object through the current provider
     *
     * @param Mail $mailToSend
     * @return array
     */
    public function sendSms($dst, $content, \DateTime $schedule = NULL, $sendAs = NULL)
    {
        if (isset($this->provider)) {
            $this->provider->sendSms($dst, $content,$schedule, $sendAs);
        }
    }
}