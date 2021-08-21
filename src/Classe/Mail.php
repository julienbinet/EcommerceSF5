<?php


namespace App\Classe;


use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

    private $api_key = '46a4c122254e94a03857efc2125e94e7';
    private $api_key_secret = '6ffc53a76c7dd1549488476db1a606a3';


    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From'             => [
                        'Email' => "julien.binet.dev@gmail.com",
                        'Name'  => "Ecommerce sf5",
                    ],
                    'To'               => [
                        [
                            'Email' => $to_email,
                            'Name'  => $to_name,
                        ],
                    ],
                    'TemplateID'       => 2587368,
                    'TemplateLanguage' => true,
                    'Subject'          => $subject,
                    'Variables'        => [
                        'header'  => "La boutique française dev",
                        'title'   => $subject,
                        'content' => $content,
                    ],
                ],
            ],
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
//        $response->success() && dd($response->getData());
        $response->success() ;
    }

}