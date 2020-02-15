#!/usr/bin/php
<?php
namespace PatricPoba\MtnMomo;

require_once 'vendor/autoload.php';

use PatricPoba\MtnMomo\Http\GuzzleClient;

  
class SandboxUserProvision
{
    /**
     * Returns a UUID4 string.
     *
     * @return string uuid4
     */
    public static function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
     

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
             
            // Create an apiKey
            $response = (new GuzzleClient())
                ->request('post', 'https://sandbox.momodeveloper.mtn.com/v1_0/apiuser/'. $xReferenceId . '/apikey', [], $headers);

            echo "Your Sandbox credentials : \n";
            echo "Ocp-Apim-Subscription-Key: {$primaryKey} \n" ;
            echo "UserId (X-Reference-Id)  : {$xReferenceId} \n" ;
            echo "APISecret                : {$response->apiKey} \n" ; 

        } catch (\Exception $exception) { 
            throw $exception; 
        } 
    }

}


if (!debug_backtrace()) { 

    (new SandboxUserProvision)->provisionSandboxUser(); 
}

