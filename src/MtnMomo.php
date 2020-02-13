<?php

namespace PatricPoba\MtnMomo;
  
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\Http\GuzzleClient;

class MtnMomo extends GuzzleClient
{
    /**
     * @var string the base url of the API 
     */ 
    const VERSION = '0.1';

 

    public function __construct(MtnConfig $config)
    { 
        parent::__construct();
 
        $this->setConfig($config);
    }
    

    public function setConfig(MtnConfig $config)
    {
        $this->config = $config; 

        return $this;
    }

    /**
     * @param array|null        $params 
     *
     * @return string The OAuth Token.
     */
    public function getToken($product = 'collection')
    {
 
        $url = $this->config->baseUrl . "/{$product}/token/";

        $this->config->validate($product);
 
        $encodedString = base64_encode(
            $this->config->collectionUserId . ':' . $this->config->collectionApiSecret
        );
        $headers = [
            'Authorization' => 'Basic ' . $encodedString,
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $this->config->collectionPrimaryKey
        ];


        $response = $this->request('post', $url, $params = [], $headers);
 
        return $response->json();
    }
    
}
