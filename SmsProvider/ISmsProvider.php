<?php

namespace AppFoundations\CommunicationsBundle\SmsProvider;

interface ISmsProvider {

    public function sendSms($dst, $content, \DateTime $schedule = NULL, $sendAs = NULL);

}