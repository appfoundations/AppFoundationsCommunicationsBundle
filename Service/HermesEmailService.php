<?php


namespace AppFoundations\CommunicationsBundle\Service;


use AppFoundations\CommunicationsBundle\Model\HMail;


/**
 * Class HermesEmailService
 *
 * Main entry point for the email sending capabilities
 * @package Foundations\CommunicationsBundle\Service
 */
class HermesEmailService
{

    /**
     * @var HMailDispatcherInterface
     */
    private $dispatcher;

    public function __construct(HMailDispatcherInterface $dispatcher)
    {

        $this->dispatcher = $dispatcher;
    }

    public function sendMail(HMail $mail)
    {
        $attempt = $this->dispatcher->dispatchHMailMessage($mail);

        return $attempt;
    }
}