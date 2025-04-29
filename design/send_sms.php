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

function sendSms($to, $messageBody)
{

    $username = 'benitokharyl1@gmail.com';
    $api_key = '90D8617C-2F8B-E2FB-02FD-49378AA8B722';


    // Configure HTTP basic authorization: BasicAuth
    $config = ClickSend\Configuration::getDefaultConfiguration()
        ->setUsername($username)
        ->setPassword($api_key);

    $apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(), $config);
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

function checkOverdueBooks($conn)
{
    $query = "SELECT due_date, name, phoneNumber, isbn, request_id FROM book_requests LEFT JOIN 

     students ON book_requests.student_id = students.student_id LEFT JOIN 

     books ON book_requests.book_id = books.book_id
    
        WHERE STR_TO_DATE(due_date, '%m/%d/%Y')  = CURDATE() 
        
        AND is_returned = false 
        
        AND is_overdue = false";

    if ($stmt = $conn->prepare($query)) {

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Bind the results to variables
            $stmt->bind_result($due_date, $name, $phoneNumber, $isbn, $request_id);

            while ($stmt->fetch()) {

                if (substr($phoneNumber, 0, 1) == '0') {
                    $to = '+63' . substr($phoneNumber, 1);  // Replace the leading '0' with '+63'
                } else {
                    $to = $phoneNumber;  // If there's no leading '0', leave the number as is
                }

                //Send SMS to every overdue books
                $body = "Hi $name, your borrowed Book with ISBN: $isbn is overdue today. Please return it as soon as possible. \n\n Thank you.";
                sendSms($to, $body);

                $update = "UPDATE book_requests SET is_overdue = true WHERE request_id = $request_id";
                $conn->query($update);
            }
        }
    } else {

        echo "Error preparing the query: " . $conn->error;
        return false;
    }
}



// Example usage
// sendSms('+639354957162', 'TESTING');
