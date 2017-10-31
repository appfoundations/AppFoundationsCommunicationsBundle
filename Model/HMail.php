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
     * @var HEmail[]
     */
    private $cc;

    /**
     * @var HEmail[]
     */
    private $bcc;

    /**
     * @var HContent
     */
    private $content;

    private $replyTo;

    /**
     * @var HAttachment
     */
    private $attachments;


    public function __construct()
    {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->attachments = array();
    }

    //helper constructor
    public function buildFromRawParts($from,$subject,$to,$content)
    {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->from = new HEmail($from);
        $this->subject = $subject;
        $this->to[] = new HEmail($to);
        $this->content = new HContent($content);
    }

    public function addAttachment( HAttachment $a ) {
        $this->attachments[] = $a;
    }

    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @return string
     */
    public function getSubject()
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
    public function getFrom()
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
    public function getContent()
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
        public function getTo()
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

        /**
         * @param HEmail $cc
         */
        public function addCC(HEmail $cc)
        {
            $this->cc[] = $cc;
        }

        /**
         * @param HEmail $cc
         */
        public function removeCC(HEmail $cc)
        {
            $key = array_search($cc,$this->cc);
            unset($this->cc[$key]);
        }

        /**
         * @return HEmail[]
         */
        public function getCC()
        {
            return $this->cc;
        }

        /**
         * @param HEmail[] $cc
         */
        public function setCC(array $cc)
        {
            $this->cc = $cc;
        }

        /**
         * @param HEmail $bcc
         */
        public function addBCC(HEmail $bcc)
        {
            $this->bcc[] = $bcc;
        }

        /**
         * @param HEmail $bcc
         */
        public function removeBCC(HEmail $bcc)
        {
            $key = array_search($bcc,$this->bcc);
            unset($this->bcc[$key]);
        }

        /**
         * @return HEmail[]
         */
        public function getBCC()
        {
            return $this->bcc;
        }

        /**
         * @param HEmail[] $bcc
         */
        public function setBCC(array $bcc)
        {
            $this->bcc = $bcc;
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
