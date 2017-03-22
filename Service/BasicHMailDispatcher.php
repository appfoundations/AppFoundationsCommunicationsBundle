<?php


namespace AppFoundations\CommunicationsBundle\Service;



use AppFoundations\CommunicationsBundle\EmailServiceProvider\EmailServiceProviderInterface;
use AppFoundations\CommunicationsBundle\Model\EmailProviderResult;
use AppFoundations\CommunicationsBundle\Model\HMail;
use AppFoundations\CommunicationsBundle\Model\HMailSendAttempt;

class BasicHMailDispatcher implements HMailDispatcherInterface
{

    /**
     * @var EmailServiceProviderInterface
     */
    private $emailServiceProvider;

    public function __construct(EmailServiceProviderInterface $emailServiceProvider)
    {

        $this->emailServiceProvider = $emailServiceProvider;
    }

    /**
     * @param HMail $message
     * @return HMailSendAttempt
     */
    public function dispatchHMailMessage(HMail $message)
    {
        $attempt = new HMailSendAttempt($message);
        $attempt->setProviderName($this->emailServiceProvider->getProviderName());

        try{

            $providerResult = $this->emailServiceProvider->sendHMailMessage($message);
            if (EmailProviderResult::QUEUED_TO_BE_DELIVERED == $providerResult->getStatus()){
                $attempt->setAttemptStatus(HMailSendAttempt::SENT_OK);
            }
            else{
                $attempt->setAttemptStatus(HMailSendAttempt::PROVIDER_ERROR);
            }

            $attempt->setProviderResult($providerResult);

        }catch (\Exception $e){

            $attempt->setAttemptStatus(HMailSendAttempt::ATTEMPT_ERROR);
            $attempt->setAttemptDetails(array('exception' => $e->getMessage()));
        }

        return $attempt;
    }
}