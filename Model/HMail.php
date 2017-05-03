<?php


namespace AppFoundations\CommunicationsBundle\Model;

use JsonSerializable;

/**
 * Class HMail
 *
 * Basic email message representation to be sent via the Hermes Email Service
 * @package Foundations\CommunicationsBundle\Model
 */
class HMail implements JsonSerializable
{
    /**
     * @var HEmail
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var HEmail[]
     */
    private $to;

    /**
     * @var HContent
     */
    private $content;

    private $replyTo;


    public function __construct()
    {
        $this->to = array();
    }

    //helper constructor
    public function buildFromRawParts($from,$subject,$to,$content)
    {
        $this->to = array();
        $this->from = new HEmail($from);
        $this->subject = $subject;
        $this->to[] = new HEmail($to);
        $this->content = new HContent($content);
    }


    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }


    /**
     * @return HEmail
     */
    public function getFrom(): HEmail
    {
        return $this->from;
    }

    /**
     * @param HEmail $from
     */
    public function setFrom(HEmail $from)
    {
        $this->from = $from;
    }

    /**
     * @return HContent
     */
    public function getContent(): HContent
    {
        return $this->content;
    }

    /**
     * @param HContent $content
     */
    public function setContent(HContent $content)
    {
        $this->content = $content;
    }

    /**
     * @param HEmail $to
     */
    public function addTo(HEmail $to)
    {
        $this->to[] = $to;
    }

    /**
     * @param HEmail $to
     */
    public function removeTo(HEmail $to)
    {
        $key = array_search($to,$this->to);
        unset($this->to[$key]);
    }

    /**
     * @return HEmail[]
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param HEmail[] $to
     */
    public function setTo(array $to)
    {
        $this->to = $to;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
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
            'hMail' => [
                'subject' => $this->subject,
                'from' => $this->from->jsonSerialize(),
                'to' => $this->to,
                'content' => $this->content->jsonSerialize(),
            ]
        ];
    }
}