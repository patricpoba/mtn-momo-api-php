<?php

namespace PatricPoba\MtnMomo;

use PatricPoba\MtnMomo\Exceptions\MtnMomoException;

class MtnRemittance extends MtnMomo 
{
    const PRODUCT = 'remittance';


    protected function requestUrl($endpointName, $params = []) : string
    { 
        switch ($endpointName) {
            case 'token':  
                $urlSegment = '/remittance/token/' ;
                break;

            case 'postTransaction': 
                $urlSegment = '/remittance/v1_0/transfer';
                break;
                 
            case 'getTransaction': 
                $urlSegment = "/remittance/v1_0/transfer/{$params['referenceId']}";
                break;
 
            case 'balance': 
                $urlSegment = '/remittance/v1_0/account/balance';
                break;
 
            case 'accountholder': 
                $urlSegment = "/remittance/v1_0/accountholder/{$params['accountHolderIdType']}/{$params['accountHolderId']}/active";
                break;
            
            default:
                throw new MtnMomoException("Unknown api endpoint - {$endpointName}.");
                break;
        }
 
        return $this->config->baseUrl . $urlSegment;
    }
}
