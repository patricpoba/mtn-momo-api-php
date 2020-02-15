<?php

namespace PatricPoba\MtnMomo;
  
use GuzzleHttp\ClientInterface;
use PatricPoba\MtnMomo\Exceptions\MtnMomoException;
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\Http\GuzzleClient;

class MtnMomo extends GuzzleClient
{
    /**
     * @var string the base url of the API 
     */ 
    const VERSION = '0.1';

 

    public function __construct(MtnConfig $config, ClientInterface $client = null)
    { 
        parent::__construct($client);
 
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
        // { access_token: "eyJ0eXAi7MRHUfMHikzQNBPAwZ2dVaIVrUgrbhiUb10A", token_type: "access_token", expires_in: 3600 }

        if ( ! $response->isSuccess() ) {
            throw new MtnMomoException("Error in getting token: {$url}");
        }

        return $response->access_token;
    }
    
}
