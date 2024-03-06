<?php

namespace LogiccFoundations\CommunicationsBundle\Tests\Provider;

use AppFoundations\CommunicationsBundle\EmailServiceProvider\SendGrid\SendGridProvider;
use AppFoundations\CommunicationsBundle\Model\EmailProviderResult;
use AppFoundations\CommunicationsBundle\Model\HContent;
use AppFoundations\CommunicationsBundle\Model\HEmail;
use AppFoundations\CommunicationsBundle\Model\HMail;
use AppFoundations\CommunicationsBundle\Service\SendGridFactory as ServiceSendGridFactory;
use PHPUnit\Framework\TestCase;
use SendGrid;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Personalization;
use SendGrid\Response;
use stdClass;

class DefaultControllerTest extends TestCase
{
    public function test_send_grid_provider()
    {
        $mockSendGrid = $this->createMock(SendGrid::class);
        ServiceSendGridFactory::setMock($mockSendGrid);

        $mockSendGridPersonalization = new Personalization();
        $mockSendGridPersonalization->addTo(new Email('','test@test.com') );

        $mockSendGridContent = new Content('test','test');
        $mockSendGridContent->setType('text/html');

        $mockSendGridMail = new Mail();
        $mockSendGridMail->setFrom( new Email('Admin','admin-test@test.com') );
        $mockSendGridMail->setSubject('Test Send Grid');
        $mockSendGridMail->addPersonalization($mockSendGridPersonalization);
        $mockSendGridMail->addContent($mockSendGridContent);

        $mockSendGridReponse = $this->createMock(Response::class);
        $mockSendGridReponse->method('statusCode')->willReturn(202);

        $mockSendGrid->client = $this->getMockBuilder(stdClass::class)
                     ->addMethods(['mail','send','post'])->disableOriginalConstructor()
                     ->getMock();

        $mockSendGrid->client->method('mail')->willReturn($mockSendGrid->client);
        $mockSendGrid->client->method('send')->willReturn($mockSendGrid->client);
        $mockSendGrid->client->method('post')->with($mockSendGridMail)->willReturn($mockSendGridReponse);
        
        $target = new SendGridProvider('test');

        $email = new HMail();
        $email->setFrom( new HEmail('admin-test@test.com','Admin') );
        $email->addTo(new HEmail('test@test.com') );
        $email->setSubject('Test Send Grid');
        $email->setContent(new HContent('test',HContent::HTML));

        $result = $target->sendHMailMessage($email);

        $this->assertEquals(new EmailProviderResult('QUEUED', [
            'code' => 202,
            'headers' => null,
            'body' => null,
        ]), $result);
    }
}
