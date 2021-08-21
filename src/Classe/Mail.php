<?php


namespace App\Classe;


use Mailjet\Client;
use Mailjet\Resources;

class Mail
{

//    private $api_key = $_ENV["MAIL_API_KEY"];
//    private $api_key_secret = $_ENV["MAIL_API_KEY_SECRET"];


    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($_ENV["MAIL_API_KEY"], $_ENV["MAIL_API_KEY_SECRET"], true, ['version' => 'v3.1']);
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