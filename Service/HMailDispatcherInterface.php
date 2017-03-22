<?php


namespace AppFoundations\CommunicationsBundle\Service;



namespace AppFoundations\CommunicationsBundle\Model\HMail;
namespace AppFoundations\CommunicationsBundle\Model\HMailSendAttempt;

interface HMailDispatcherInterface
{
    /**
     * @param HMail $message
     * @return HMailSendAttempt
     */
    public function dispatchHMailMessage(HMail $message);
}