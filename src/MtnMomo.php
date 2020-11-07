<?php

namespace PatricPoba\MtnMomo;
  
use GuzzleHttp\ClientInterface;
use PatricPoba\MtnMomo\MtnConfig;
use PatricPoba\MtnMomo\Http\GuzzleClient;
use PatricPoba\MtnMomo\Utilities\Helpers;
use PatricPoba\MtnMomo\Exceptions\MtnMomoException;

abstract class MtnMomo extends GuzzleClient
{
    use Helpers;
    
    /**
     * @var string the base url of the API 
     */ 
    const VERSION = '1.0';

    protected $config;
 
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

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the auth token
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
            throw new MtnMomoException("Error in getting token: {$url}. Response: " . (string) $response);
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


    public function getTransaction(string $transactionId)
    {
        $headers = [
            'Authorization'             => 'Bearer ' . $this->getToken() ,
            'Content-Type'              => 'application/json',
            "X-Target-Environment"      => $this->config->targetEnvironment,
            'Ocp-Apim-Subscription-Key' => $this->config->getValue(static::PRODUCT, 'primaryKey')
        ];
        
        $url = $this->requestUrl('getTransaction', ['referenceId'=> $transactionId]);

        return $this->request('get', $url, $params = [], $headers);
    }
 
    /**
     * Make a requestToPay (collection product) or transfer (disbursement product) or transfer (remittance product)
     *
     * @param array $params amount, mobileNumber,payeeNote,payerMessage,externalId, callbackUrl*
     * @param string $transactionUuid
     * @throws \Exception
     * @return mixed string | PatricPoba\MtnMomo\Http\ApiReponse
     */
    public function createTransaction(array $data, string $transactionUuid = null)
    {
        $params = [
            "amount"            => $data['amount'],
            "currency"          => $data['currency'] ?? $this->config->currency,
            "externalId"        => $data['externalId'], 
            /** key can be either "payee" or payer depending on the product */
            (static::PRODUCT === 'collection' ? 'payer' : 'payee') => [
                "partyIdType"   => $data['partyIdType'] ?? 'MSISDN',
                "partyId"       => $data['mobileNumber']
            ], 
            "payerMessage"      => $data['payerMessage'],
            "payeeNote"         => $data['payeeNote']
        ];

        $transactionUuid = $transactionUuid ?? static::uuid();

        $headers = [
            'Authorization'             => 'Bearer ' . $this->getToken() ,
            'Content-Type'              => 'application/json',
            "X-Target-Environment"      => $this->config->targetEnvironment,
            'Ocp-Apim-Subscription-Key' => $this->config->getValue(static::PRODUCT, 'primaryKey'), 
            "X-Reference-Id"            => $transactionUuid
        ];
        
        $defaultCallbackUrlInEnv = trim($this->config->getValue(static::PRODUCT, 'callbackUrl'));
        
        if (!empty($params['callbackUrl'])) {
            $headers['X-Callback-Url'] = $params['callbackUrl']; 

        }elseif(strlen($defaultCallbackUrlInEnv) > 1){
            $headers['X-Callback-Url'] = $defaultCallbackUrlInEnv; 
        } 

        $response = $this->request('post', $this->requestUrl('createTransaction'), $params, $headers);
 
        return $response->isSuccess() ? $transactionUuid : $response;
    }
 
    /**
     * Undocumented function
     *
     * @param [type] $mobileNumber
     * @return void
     */
    public function accountHolderActive($mobileNumber)
    {
        $headers = [
            'Authorization'             => 'Bearer ' . $this->getToken() ,
            'Content-Type'              => 'application/json',
            "X-Target-Environment"      => $this->config->targetEnvironment,
            'Ocp-Apim-Subscription-Key' => $this->config->getValue(static::PRODUCT, 'primaryKey')
        ];

        $url = $this->requestUrl('accountholderActive', ['accountHolderId'=> $mobileNumber]) ;
  
        return $this->request('get', $url, $params = [], $headers);
    }
    
    
    abstract protected function requestUrl(string $endpointName, array $params = []) : string;

    
}
