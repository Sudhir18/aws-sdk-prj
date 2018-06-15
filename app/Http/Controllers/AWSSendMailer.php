<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;

class AWSSendMailer extends Controller
{
    //

    public function sendMail(){

    $to = "sudhir.sawant@ambab.com";
    $subject = "Test msg";

    $data = ['name' => 'sudhir'];
    $body = view('emails.welcome',$data)->render();
    $mail_from = env('MAIL_FROM');

    
    $charset = "UTF-8"; 
    $config = config('services.ses');
    $client = SesClient::factory($config);
    try {
     $result = $client->sendEmail([
    'Destination' => [
        'ToAddresses' => [
            $to,
        ],
    ],
    'Message' => [
        'Body' => [
            'Html' => [
                'Charset' => $charset,
                'Data' => $body,
            ],
			'Text' => [
                'Charset' => $charset,
                'Data' =>$body,
            ],
        ],
        'Subject' => [
            'Charset' => $charset,
            'Data' => $subject,
        ],
    ],
    'Source' => $mail_from,
]);
     $messageId = $result->get('MessageId');
     echo("Email sent! Message ID: $messageId"."\n");

} catch (SesException $error) {
	dd($error);
     echo("The email was not sent. Error message: ".$error->getAwsErrorMessage()."\n");
}
    }
}
