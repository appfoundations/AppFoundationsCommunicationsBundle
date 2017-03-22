<?php


namespace AppFoundations\CommunicationsBundle\EmailServiceProvider\Dummy;




use AppFoundations\CommunicationsBundle\EmailServiceProvider\EmailServiceProviderInterface;
use AppFoundations\CommunicationsBundle\Model\EmailProviderResult;
use AppFoundations\CommunicationsBundle\Model\HMail;

class DummyEmailProvider implements EmailServiceProviderInterface
{

    /**
     * @param HMail $message
     * @return EmailProviderResult
     */
    public function sendHMailMessage(HMail $message)
    {
        $dummyResult = new EmailProviderResult(
            EmailProviderResult::QUEUED_TO_BE_DELIVERED,
            array('message'=>$message));
        return $dummyResult;
    }

    /**
     * @return string Provider Name
     */
    public function getProviderName()
    {
        return "DummyProvider";
    }
}