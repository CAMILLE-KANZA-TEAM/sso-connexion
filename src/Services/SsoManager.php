<?php

namespace App\Services;
use Hybridauth;

class SsoManager
{
    private $config;


    public function __construct()
    {


        $this->config = [
            /**
             * Set the Authorization callback URL to https://path/to/hybridauth/examples/example_06/callback.php.
             * Understandably, you need to replace 'path/to/hybridauth' with the real path to this script.
             */
            'callback' => 'http://localhost:8002/callback/',
            'providers' => [
                'Google' => [
                    'enabled' => true,
                    'keys' => [
                        'key' => '197341954286-jespr46sh8lncvnfr3jhfr0gijr0nsr5.apps.googleusercontent.com',
                        'secret' => '-3xQR7ze4LjUC4kbOkhE5oFl',
                    ],
                ],
                'LinkedIn' => [
                    'enabled' => true,
                    'keys' => [
                        'id' => '86pioes1rmblwc',
                        'secret' => '2JRHS2AufWnuFRTc',
                    ],
                ]
            ],
        ];

    }

    public function getConfig()
    {
        return $this->config;
    }

    // https://hybridauth.github.io/documentation.html
    // https://github.com/hybridauth/hybridauth


    // Cf doc :
    public function getProviders()
    {

        $hybridauth = new Hybridauth\Hybridauth($this->config);
        return $hybridauth->getProviders();
    }


    public function getCallBack()
    {
        $callback = $this->config['callback'];
        return $callback;
    }



    public function authenticate()
    {
        $config = [
            'callback' => 'https://example.com/path/to/script.php',
            'keys' => [
                'key' => 'your-twitter-consumer-key',
                'secret' => 'your-twitter-consumer-secret',
            ],
        ];

        try {
            $google = new Hybridauth\Provider\Google($config);
            $google->authenticate();

            $accessToken = $google->getAccessToken();
            $userProfile = $google->getUserProfile();
            $apiResponse = $google->apiRequest('statuses/home_timeline.json');
        } catch (\Exception $e) {
            echo 'Oops, we ran into an issue! ' . $e->getMessage();
        }
    }




}