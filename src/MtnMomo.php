<?php

namespace PatricPoba\MtnMomo;
  
use GuzzleHttp\ClientInterface;
use PatricPoba\MtnMomo\Exceptions\MtnMomoException;
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\Http\GuzzleClient;

abstract class MtnMomo extends GuzzleClient
{
    /**
     * @var string the base url of the API 
     */ 
    const VERSION = '0.1';

    /**
     * Current product, would be overriden by the child classes
     * Example: 'collection', 'disbursement', 'remittance'
     */
    const PRODUCT = null; 
 

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
    public function getToken() : string
    {  
        $this->config->validate(static::PRODUCT);
        
        $encodedString = base64_encode(
            // $this->config->collectionUserId . ':' . $this->config->collectionApiSecret
            $this->config->getValue(static::PRODUCT, 'userId') . ':' . $this->config->getValue(static::PRODUCT, 'apiSecret')
        );
        
        $headers = [
            'Authorization'             => 'Basic ' . $encodedString,
            'Content-Type'              => 'application/json', 
            'Ocp-Apim-Subscription-Key' => $this->config->getValue(static::PRODUCT, 'primaryKey')
        ];
          
        $response = $this->request('post', $url = $this->requestUrl('token'), $params = [], $headers);
        // { access_token: "eyJ0eXAi7MRHUfMHikzQNBPAwZ2dVaIVrUgrbhiUb10A", token_type: "access_token", expires_in: 3600 }

        if ( ! $response->isSuccess() ) {
            throw new MtnMomoException("Error in getting token: {$url}");
        }

        return $response->access_token;
    }


    public function getBalance()
    {  
        $headers = [
            'Authorization'             => 'Bearer ' . $this->getToken() ,
            'Content-Type'              => 'application/json',
            "X-Target-Environment"      => $this->config->targetEnvironment,
            'Ocp-Apim-Subscription-Key' => $this->config->getValue(static::PRODUCT, 'primaryKey')
        ];
  
        return $this->request('get', $this->requestUrl('balance'), $params = [], $headers);
    }


    public function getTransaction(string $trasactionId)
    {
        $headers = [
            'Authorization'             => 'Bearer ' . $this->getToken() ,
            'Content-Type'              => 'application/json',
            "X-Target-Environment"      => $this->config->targetEnvironment,
            'Ocp-Apim-Subscription-Key' => $this->config->getValue(static::PRODUCT, 'primaryKey')
        ];
 
        $url = $this->config->baseUrl . '/'. static::PRODUCT . "/v1_0/requesttopay/{$trasactionId}"; 
        
        return $this->request('get', $url, $params = [], $headers);
    }
    
    
    abstract protected function requestUrl(string $endpointName, array $params = []) : string;

    
}
