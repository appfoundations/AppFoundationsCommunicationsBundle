<?php


namespace AppFoundations\CommunicationsBundle\EmailServiceProvider\SendGrid;



use AppFoundations\CommunicationsBundle\EmailServiceProvider\EmailServiceProviderInterface;
use AppFoundations\CommunicationsBundle\Model\EmailProviderResult;
use AppFoundations\CommunicationsBundle\Model\HContent;
use AppFoundations\CommunicationsBundle\Model\HMail;
use SendGrid;
use SendGrid\Mail\Content;
use SendGrid\Mail\To;
use SendGrid\Mail\Mail;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\ReplyTo;
use AppFoundations\CommunicationsBundle\Model\HAttachment;
use SendGrid\Mail\Attachment;

/**
 * Email provider leveraging Sengrid APi v3
 * docs: https://sendgrid.com/docs/API_Reference/api_v3.html
 * docs: https://github.com/sendgrid/sendgrid-php
 * Class SendGridProvider
 * @package AppFoundations\CommunicationsBundle\EmailServiceProvider\SendGrid
 */
class SendGridProvider implements EmailServiceProviderInterface
{

    public function __construct(
        private $sendGridKey = null,
        private $region = null)
    {}

    /**
     * @param HMail $message
     * @return EmailProviderResult
     * @throws \Exception
     */
    public function sendHMailMessage(HMail $message)
    {
        $sendGridMessage = $this->buildFromHMail($message);

        if (!$this->sendGridKey){
            throw new \Exception("No send grid key defined");
        }

        $sg = new SendGrid($this->sendGridKey);
        if (!empty($this->region)) {
            $sg->setDataResidency($this->region);
        }

        $response = $sg->client->mail()->send()->post($sendGridMessage);
        $result = array();
        $result['code'] = $response->statusCode();
        $result['headers'] = $response->headers();
        $result['body'] = $response->body();

        $providerResult = $this->buildProviderResult($result);

        return $providerResult;
    }

    private function buildProviderResult($rawResult)
    {
        if ($rawResult['code']==202){
            $status = EmailProviderResult::QUEUED_TO_BE_DELIVERED;
        }else{
            $status = EmailProviderResult::UNMAPPED_ERROR;
        }
        $result = new EmailProviderResult($status,$rawResult);
        return $result;
    }

    private function buildFromHMail(HMail $message)
    {
        $mail = new Mail();
        $mail->setFrom($message->getFrom()->getAddress(), $message->getFrom()->getName());

        $mail->setSubject($message->getSubject());

        $personalization = new Personalization();
        foreach ($message->getTo() as $to){
            $email = new To($to->getAddress(), $to->getName());
            $personalization->addTo($email);
        }
        foreach ($message->getCC() as $to){
            $email = new To($to->getAddress(), $to->getName());
            $personalization->addCc($email);
        }
        foreach ($message->getBCC() as $to){
            $email = new To($to->getAddress(), $to->getName());
            $personalization->addBcc($email);
        }

        $mail->addPersonalization($personalization);

        if ($message->getReplyTo() != null) {
            $mail->setReplyTo( new ReplyTo( $message->getReplyTo()->getAddress() ) );
        }

        $content = null;
        switch ($message->getContent()->getType()){
            case HContent::PLAIN_TEXT:
                $content = new Content("text/plain", $message->getContent()->getText());
                break;
            case HContent::HTML:
                $content = new Content("text/html", $message->getContent()->getText());
                break;
        }

        if (!$content){
            throw new \Exception("No email content defined");
        }

        $mail->addContent($content);

        foreach ($message->getAttachments() as $attachment) {
            /* @var HAttachment $attachment */
            $a = new Attachment();
            $a->setContent( base64_encode($attachment->getContent()) );
            $contentId = $attachment->getContentID();
            if (!empty($contentId)) {
                $a->setContentID( $attachment->getContentID() );
            }
            $disposition = $attachment->getDisposition();
            if (!empty($disposition)) {
                $a->setDisposition( $disposition );
            }
            $a->setFilename( $attachment->getFilename() );
            $type = $attachment->getType();
            if (!empty($type)) {
                $a->setType( $type );
            }
            $mail->addAttachment( $a );
        }

        return $mail;
    }

    /**
     * @return string Provider Name
     */
    public function getProviderName()
    {
        return "SendGrid";
    }
}
