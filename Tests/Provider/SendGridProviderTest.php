<?php

namespace LogiccFoundations\CommunicationsBundle\Tests\Provider;

use AppFoundations\CommunicationsBundle\EmailServiceProvider\SendGrid\SendGridProvider;
use AppFoundations\CommunicationsBundle\Model\HContent;
use AppFoundations\CommunicationsBundle\Model\HEmail;
use AppFoundations\CommunicationsBundle\Model\HMail;
use AppFoundations\CommunicationsBundle\Service\SendGridFactory as ServiceSendGridFactory;
use PHPUnit\Framework\TestCase;
use SendGrid;

class DefaultControllerTest extends TestCase
{
    public function test_send_grid_provider()
    {
        $mockSendGrid = $this->createMock(SendGrid::class);
        ServiceSendGridFactory::setMock($mockSendGrid);
        $sendGridFactory = ServiceSendGridFactory::createSendGrid();


        $target = new SendGridProvider('test');

        $email = new HMail();
        $email->setFrom( new HEmail('admin-test@test.com','Admin') );
        $email->addTo(new HEmail('test@test.com') );
        $email->setSubject('Test Send Grid');
        $email->setContent(new HContent('test',HContent::HTML));

        $result = $target->sendHMailMessage($email);

        var_dump($result);

        $this->assertInstanceOf($sendGridFactory, $result);

    }
}
