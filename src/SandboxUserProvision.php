#!/usr/bin/php
<?php
namespace PatricPoba\MtnMomo;

require_once 'vendor/autoload.php';

use PatricPoba\MtnMomo\Http\GuzzleClient;
use PatricPoba\MtnMomo\Utilities\Helpers;

  
class SandboxUserProvision
{
    use Helpers;
     

    protected function validateInput($primaryKey, $providerCallbackHost, $xReferenceId)
    { 
        $commandLineOptions = getopt("k:c:x::");
        $optionsErrorMessage = 'The options -k (primaryKey) and -c (callbackDomain) are both required.';
 
        if (is_null($primaryKey)) { 
            if (! isset($commandLineOptions['k'])) {
                exit($optionsErrorMessage);  
            }
            $primaryKey = $commandLineOptions['k']; 
        }
        
        if (is_null($providerCallbackHost)) { 
            if (! isset($commandLineOptions['c'])) {
                exit($optionsErrorMessage); 
            } 
            $providerCallbackHost = $commandLineOptions['c']; 
        }

        if (is_null($xReferenceId)) {
            $xReferenceId = static::uuid();
        }
  
        return [$xReferenceId, $primaryKey, $providerCallbackHost];
    }


    public function provisionSandboxUser( $primaryKey = null, $providerCallbackHost = null, $xReferenceId = null)
    { 
        list($xReferenceId, $primaryKey, $providerCallbackHost) = $this->validateInput($primaryKey, $providerCallbackHost, $xReferenceId);

        $headers = [
            'X-Reference-Id'            => $xReferenceId,
            'Content-Type'              => 'application/json',
            'Ocp-Apim-Subscription-Key' => $primaryKey,
        ];
        $params = [ 'providerCallbackHost' => $providerCallbackHost ];
   
        try {
            // Create a sandbox user
            $response = (new GuzzleClient())
                ->request('post', 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser', $params, $headers);

            if (! $response->isSuccess()) {
                exit($response);
            }
 
            // Create an apiKey
            $response = (new GuzzleClient())
                ->request('post', 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/'. $xReferenceId . '/apikey', [], $headers);

            if ( ! $response->isSuccess()) {
                exit($response); 
            }

            echo "Your Sandbox credentials : \n";
            echo "Ocp-Apim-Subscription-Key: {$primaryKey} \n" ;
            echo "UserId (X-Reference-Id)  : {$xReferenceId} \n" ; 
            echo "ApiKey (ApiSecret)       : {$response->apiKey} \n" ;  
            echo "Callback host            : {$providerCallbackHost} \n" ; 

        } catch (\Exception $exception) { 
            throw $exception; 
        } 
    }

}


if (!debug_backtrace()) { 

    (new SandboxUserProvision)->provisionSandboxUser(); 
}

