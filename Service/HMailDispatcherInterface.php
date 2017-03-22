<?php


namespace AppFoundations\CommunicationsBundle\Service;



use AppFoundations\CommunicationsBundle\Model\HMail;
use AppFoundations\CommunicationsBundle\Model\HMailSendAttempt;

interface HMailDispatcherInterface
{
    /**
     * @param HMail $message
     * @return HMailSendAttempt
     */
    public function dispatchHMailMessage(HMail $message);
}