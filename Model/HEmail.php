<?php


namespace AppFoundations\CommunicationsBundle\Model;

use JsonSerializable;


class HEmail implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    public function __construct($address, $name = null)
    {
        $this->name = $name;
        $this->address = $address;
    }

    /**
     * @return NULL|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
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
            'hEmail' => [
                'name' => $this->name,
                'address' => $this->address,
            ]
        ];
    }
}