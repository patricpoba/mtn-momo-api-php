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

        $this->config = $config;
    }

    /**
     * @param array|null        $params 
     *
     * @return string The OAuth Token.
     */
    public function getToken()
    {
 
        // $url = $this->_baseUrl . '/collection/token/';


        // $encodedString = base64_encode(
        //     MomoApi::getCollectionUserId() . ':' . MomoApi::getCollectionApiSecret()
        // );
        // $headers = [
        //     'Authorization' => 'Basic ' . $encodedString,
        //     'Content-Type' => 'application/json',
        //     'Ocp-Apim-Subscription-Key' => MomoApi::getCollectionPrimaryKey()
        // ];


        // $response = self::request('post', $url, $params, $headers);


        // $obj = ResourceFactory::accessTokenFromJson($response->json);

        // return $obj;
    }
    
}
