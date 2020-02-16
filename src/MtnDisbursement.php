<?php

namespace PatricPoba\MtnMomo;

use PatricPoba\MtnMomo\Exceptions\MtnMomoException;

class MtnDisbursement extends MtnMomo 
{
    const PRODUCT = 'disbursement';


    protected function requestUrl($endpointName, $params = []) : string
    { 
        switch ($endpointName) {
            case 'token':  
                $urlSegment = '/disbursement/token/' ;
                break;

            case 'postTransaction': 
                $urlSegment = '/disbursement/v1_0/transfer';
                break;
                 
            case 'getTransaction': 
                $urlSegment = "/disbursement/v1_0/transfer/{$params['referenceId']}";
                break;
 
            case 'balance': 
                $urlSegment = '/disbursement/v1_0/account/balance';
                break;
 
            case 'accountholder': 
                $urlSegment = "/disbursement/v1_0/accountholder/{$params['accountHolderIdType']}/{$params['accountHolderId']}/active";
                break;
            
            default:
                throw new MtnMomoException("Unknown api endpoint - {$endpointName}.");
                break;
        }
 
        return $this->config->baseUrl . $urlSegment;
    }
    
}
