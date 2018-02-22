<?php


namespace AppFoundations\CommunicationsBundle\Model;

use JsonSerializable;

/**
 * Class EmailProviderResult
 *
 * An abstraction layer representing the result of an email sending operation via a provider
 * @package Foundations\CommunicationsBundle\Model
 */
class EmailProviderResult implements JsonSerializable
{
    //These would be inner Hermes status code common to all email service providers
    const QUEUED_TO_BE_DELIVERED = "QUEUED";
    const UNMAPPED_ERROR = "ERROR";

    /**
     * @var string
     */
    private $status;


    /**
     * @var array
     */
    private $rawResults;

    public function __construct($status, $rawResults)
    {

        $this->status = $status;

        $this->rawResults = $rawResults;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getRawResults()
    {
        return $this->rawResults;
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
            'providerResult' => [
                'status' => $this->status,
                'rawResults' => $this->rawResults
            ]
        ];
    }
}