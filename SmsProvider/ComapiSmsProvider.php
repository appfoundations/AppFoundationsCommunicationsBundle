<?php


namespace AppFoundations\CommunicationsBundle\SmsProvider;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\StreamHandler;

/**
 * SMS provider leveraging Dynmark Comapi API
 * docs: https://docs.comapi.com/reference#oneapi-calling-the-one-api
 * Class ComapiSmsProvider
 * @package AppFoundations\CommunicationsBundle\SmsProvider
 *
 */
class ComapiSmsProvider implements ISmsProvider
{
    private $apiSpace;
    private $token;
    private $senderId;

    public function __construct( $config) {

        $this->apiSpace = $config['comapi']['space'];
        $this->token = $config['comapi']['token'];
        $this->senderId = $config['comapi']['sender'];
        if (empty($this->senderId)){
            $this->senderId = "Not set";
        }
    }

    public function sendSms($dst, $content, \DateTime $schedule = NULL, $sendAs = NULL)
    {
        error_log($dst);
        error_log($content);

        $fullRecipient = (strlen($dst) < 12) ? ("44".$dst) : $dst;


        $handler = new StreamHandler();
        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);


        try {
            $response = $client->request('POST',"https://api.comapi.com/apispaces/{$this->apiSpace}/messages", [
                'headers' => [
                    'cache-control' => 'no-cache',
                    'Accept'     => 'application/json',
                    'Authorization' => "Bearer {$this->token}"
                ],
                'json' => [
                    'body' => urldecode($content),
                    'to' => ['phoneNumber' => $fullRecipient],
                    'channelOptions' => [
                        'sms' => ['from' => (isset($sendAs) && !empty($sendAs)) ? $sendAs : $this->senderId]
                    ],
                    'rules' => ['sms']
                ]
            ]);

            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK

            error_log(Psr7\str($response));

            return [
                'status' => 'OK',
                'details' => ['code' => $code,'reason' => $reason]
                ];
        } catch (RequestException $e) {
            $requestStr = Psr7\str($e->getRequest());
            $responseStr = null;
            if ($e->hasResponse()) {
                $responseStr = Psr7\str($e->getResponse());
            }
            return [
                'status' => 'NOK',
                'details' => ['request' => $requestStr, 'response' => $responseStr]
            ];
        }
    }
}