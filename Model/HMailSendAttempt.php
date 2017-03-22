<?php


namespace AppFoundations\CommunicationsBundle\Model;

use JsonSerializable;

/**
 * Class HMailSendAttempt
 *
 * Represent a traceable attempt to send an HMail message
 * @package Foundations\CommunicationsBundle\Model
 */
class HMailSendAttempt implements JsonSerializable
{
    //These will contain all possible statuses of an attempt to send an HMail message
    const SENT_OK = "SENT OK";
    const PROVIDER_ERROR = "PROVIDER ERROR";
    const ATTEMPT_ERROR = "ATTEMPT ERROR";

    /**
     * @var string
     */
    private $attemptStatus;

    /**
     * @var string
     */
    private $providerName;

    /**
     * @var array
     */
    private $attemptDetails;

    /**
     * @var EmailProviderResult
     */
    private $providerResult;

    /**
     * @var HMail
     */
    private $message;

    public function __construct(HMail $message)
    {
        $this->message = $message;
    }

    /**
     * @return NULL|array
     */
    public function getAttemptDetails()
    {
        return $this->attemptDetails;
    }

    /**
     * @param array $attemptDetails
     */
    public function setAttemptDetails(array $attemptDetails = null)
    {
        $this->attemptDetails = $attemptDetails;
    }

    /**
     * @return NULL|EmailProviderResult
     */
    public function getProviderResult()
    {
        return $this->providerResult;
    }

    /**
     * @param EmailProviderResult $providerResult
     */
    public function setProviderResult(EmailProviderResult $providerResult = null)
    {
        $this->providerResult = $providerResult;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'hMailSendAttempt' => [
                'attemptStatus' => $this->attemptStatus,
                'message' => $this->message->jsonSerialize(),
                'providerName' => $this->providerName,
                'provider' => $this->providerResult->jsonSerialize(),
                'attemptDetails' => $this->attemptDetails
            ]
        ];
    }

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }

    /**
     * @param string $providerName
     */
    public function setProviderName(string $providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * @return string
     */
    public function getAttemptStatus(): string
    {
        return $this->attemptStatus;
    }

    /**
     * @param string $attemptStatus
     */
    public function setAttemptStatus(string $attemptStatus)
    {
        $this->attemptStatus = $attemptStatus;
    }

    /**
     * @return HMail
     */
    public function getMessage(): HMail
    {
        return $this->message;
    }
}