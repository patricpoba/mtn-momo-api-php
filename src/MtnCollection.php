<?php

namespace PatricPoba\MtnMomo;

use PatricPoba\MtnMomo\Exceptions\MtnMomoException;

class MtnCollection extends MtnMomo 
{
    const PRODUCT = 'collection';
 

    protected function requestUrl(string $endpointName, array $params = []) : string
    { 
        switch ($endpointName) {
            case 'token':  
                $urlSegment = '/collection/token/' ; // trailing slash mandatory
                break;

            case 'createTransaction': 
                $urlSegment = '/collection/v1_0/requesttopay';
                break;
                 
            case 'getTransaction': 
                $urlSegment = "/collection/v1_0/requesttopay/{$params['referenceId']}";
                break;
 
            case 'balance': 
                $urlSegment = '/collection/v1_0/account/balance';
                break;
 
            case 'accountholderActive': 
                $urlSegment = "/collection/v1_0/accountholder/MSISDN/{$params['accountHolderId']}/active";
                break;
            
            default:
                throw new MtnMomoException("Unknown Api endpoint - {$endpointName}.");
                break;
        }
 
        return $this->config->baseUrl . $urlSegment;
    }
 
   
    public function requestToPay(array $data, string $transactionUuid = null)
    {
        return parent::createTransaction($data, $transactionUuid);
    }

}
