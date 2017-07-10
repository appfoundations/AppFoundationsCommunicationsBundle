<?php


namespace AppFoundations\CommunicationsBundle\EmailServiceProvider\SendGrid;



use AppFoundations\CommunicationsBundle\EmailServiceProvider\EmailServiceProviderInterface;
use AppFoundations\CommunicationsBundle\Model\EmailProviderResult;
use AppFoundations\CommunicationsBundle\Model\HContent;
use AppFoundations\CommunicationsBundle\Model\HMail;
use SendGrid;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Personalization;
use SendGrid\ReplyTo;
use AppFoundations\CommunicationsBundle\Model\HAttachment;
use SendGrid\Attachment;


class SendGridProvider implements EmailServiceProviderInterface
{

    /**
     * @var
     */
    private $sendGridKey;

    public function __construct($sendGridKey = null)
    {
        $this->sendGridKey = $sendGridKey;
    }

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
        $email = new Email($message->getFrom()->getName(), $message->getFrom()->getAddress());
        $mail->setFrom($email);

        $mail->setSubject($message->getSubject());

        $personalization = new Personalization();
        foreach ($message->getTo() as $to){
            $email = new Email($to->getName(), $to->getAddress());
            $personalization->addTo($email);
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
            $a->setContent( $attachment->getContent() );
            $a->setContentID( $attachment->getContentID() );
            $a->setDisposition( $attachment->getDisposition() );
            $a->setFilename( $attachment->getFilename() );
            $a->setType( $attachment->getType() );
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