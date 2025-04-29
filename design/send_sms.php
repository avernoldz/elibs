<?php
/**
 * Function to send SMS using ClickSend API.
 * 
 * @param string $to The recipient phone number (including country code).
 * @param string $from The sender name or phone number (can be alphanumeric or numeric).
 * @param string $body The content of the message.
 * @return string The result of the SMS sending operation.
 */

use ClickSend\Api\SmsApi;
use ClickSend\Configuration;
use ClickSend\Model\SmsMessage;
use ClickSend\ApiException;

function sendSms($to, $messageBody) {
    
    $username = 'benitokharyl1@gmail.com';
    $api_key = '90D8617C-2F8B-E2FB-02FD-49378AA8B722';

    
    // Configure HTTP basic authorization: BasicAuth
    $config = ClickSend\Configuration::getDefaultConfiguration()
                  ->setUsername($username)
                  ->setPassword($api_key);
    
    $apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(),$config);
    $msg = new \ClickSend\Model\SmsMessage();
    $msg->setBody($messageBody); 
    $msg->setTo($to);
    $msg->setSource("sdk");
    
    // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
    $sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
    $sms_messages->setMessages([$msg]);
    
    try {
        $result = $apiInstance->smsSendPost($sms_messages);
        // print_r($result);
    } catch (Exception $e) {
        echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
    }
    
}

// Example usage
// sendSms('+639354957162', 'TESTING');
?>