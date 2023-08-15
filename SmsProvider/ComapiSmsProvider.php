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
        $fullRecipient = (strlen($dst) < 12) ? ("44".$dst) : $dst;
        
        
        $handler = new StreamHandler();
        $stack = HandlerStack::create($handler);
        $client = new Client(['handler' => $stack]);
        $body = [
            'body' => urldecode($content),
            'to' => ['phoneNumber' => $fullRecipient],
            'channelOptions' => [
                'sms' => ['from' => (isset($sendAs) && !empty($sendAs)) ? $sendAs : $this->senderId]
            ],
            'rules' => ['sms']
        ];
        
        try {
            $response = $client->request('POST',"https://api.comapi.com/apispaces/{$this->apiSpace}/messages", [
                'headers' => [
                    'cache-control' => 'no-cache',
                    'Accept'     => 'application/json',
                    'Authorization' => "Bearer {$this->token}"
                    ],
                    'json' => $body
                    ]);
            
            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            
            
            return [
                'status' => 'OK',
                'details' => ['code' => $code,'reason' => $reason, 'dst' => $fullRecipient, 'content' => $content]
            ];
        } catch (RequestException $e) {
            $requestStr = var_export($e->getRequest(), true);
            $responseStr = null;
            if ($e->hasResponse()) {
                $responseStr = var_export($e->getResponse(), true);
            }
            return [
                'status' => 'NOK',
                'details' => ['request' => $requestStr, 'response' => $responseStr, $body => $body]
            ];
        }
    }
}