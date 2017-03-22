<?php


namespace AppFoundations\CommunicationsBundle\EmailServiceProvider;



use AppFoundations\CommunicationsBundle\Model\EmailProviderResult;
use AppFoundations\CommunicationsBundle\Model\HMail;

interface EmailServiceProviderInterface
{
    /**
     * @param HMail $message
     * @return EmailProviderResult
     */
    public function sendHMailMessage(HMail $message);

    /**
     * @return string Provider Name
     */
    public function getProviderName();
}