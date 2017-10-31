<?php


namespace AppFoundations\CommunicationsBundle\Model;

use JsonSerializable;

class HContent implements JsonSerializable
{

    const PLAIN_TEXT = "text";
    const HTML = "html";

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $text;

    public function __construct($text, $type = self::PLAIN_TEXT)
    {

        $this->text = $text;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
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
            'hContent' => [
                'type' => $this->type,
                'content' => "Not included"
            ]
        ];
    }
}