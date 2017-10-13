<?php

namespace AppFoundations\CommunicationsBundle\SmsProvider;

use DateInterval;
use SoapClient;

class DynmarkSmsProvider implements ISmsProvider {

    private $sendSMSAfter;
    private $username;
    private $password;

    public function __construct( $config) {
        $this->sendSMSAfter = $config['minhour'];
        $this->username = $config['dynmark']['username'];
        $this->password = $config['dynmark']['password'];
    }

    public function sendSms($dst, $content, \DateTime $schedule = NULL, $sendAs = NULL) {

        error_log( $dst );
        error_log( $content );

//         try {
            // Configuration variables

            $client = new SoapClient("https://services.dynmark.com/WebServices/MessagingServicesWS.asmx?wsdl", array(
                "connection_timeout" => 130,
            ));

            $fullRecipient = (strlen($dst) < 12) ? ("44".$dst) : $dst;

            $message = array (
                "recipients" => array(
                    $fullRecipient
                ),
                "text" => urldecode($content),
                "originator" => (isset($sendAs) && !empty($sendAs)) ? $sendAs : "447860034533",
                "validUntil" => "9999-01-01T00:00:00",
            );

            if ($schedule != null) {
                $message["deliverAfter"] = $this->setDateAfterHour($schedule, $this->sendSMSAfter)->format('Y-m-d\\TH:i:s');
            } else {
                $message["deliverAfter"] = $this->setDateAfterHour(new \DateTime(), $this->sendSMSAfter)->format('Y-m-d\\TH:i:s');;
            }

            $params = array(
                "name" => $this->username, //"pantherwarehousing",
                "password" => $this->password, //"5a5mg96y2Qu5c7942guR",
                "message" => array(
                    $message,
                ),
            );

            $response = $client->__soapCall("SendMessageCollection", array($params));

            error_log( var_export($response, true) );

            return empty($response->returnCode);
//         } catch (Exception $e) {
//             return false;
//         }
    }

    function setDateAfterHour(\DateTime $date, $hour) {
        $hours = intval($date->format('G'));
        $minutes = intval($date->format('i'));

        if($hours < $hour) {
            $diffH = 7 - $hours;
            $diffM = 60 - $minutes;
            if($diffM < 60) {
                $diffH = $diffH - 1;
                $date->add(new DateInterval('PT'.$diffH.'H'));
                $date->add(new DateInterval('PT'.$diffM.'M'));
            } else {
                $date->add(new DateInterval('PT'.$diffH.'H'));
            }
            return $date;
        }

        return $date;
    }

}