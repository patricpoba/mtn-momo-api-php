<?php

namespace PatricPoba\MtnMomo;

use PatricPoba\MtnMomo\Utilities\AttributesMassAssignable;

class MtnConfig 
{
    use AttributesMassAssignable;

    /**
     * @var string target environment
     */
    public $baseUrl;
 
    /**
     * @var string the currency of http calls
     */
    public $targetEnvironment;
 
    /**
     * @var string The MTN OpenApi currency
     */
    public $currency;


    /**
     * @var string The MTN OpenApi collections credentials
     *  - API Secret.
     *  - Primary Key
     *  - User Id
     */
    public $collectionApiSecret;

    public $collectionPrimaryKey;

    public $collectionUserId;
     

    /**
     * @var string The MTN OpenApi remittance credentials 
     *  - API Secret.
     *  - Primary Key
     *  - User Id
     */
    public $remittanceApiSecret;

    public $remittancePrimaryKey;

    public $remittanceUserId;
 

    /**
     * @var string The MTN OpenApi disbursements primary Key
     *  - API Secret.
     *  - Primary Key
     *  - User Id
     */
    public $disbursementApiSecret;
 
    public $disbursementPrimaryKey;
 
    public $disbursementUserId;


    /**
     * Checck if all credentials are filled for the necessary
     * product 
     *
     * @param string $product disbursement | remittance | collection
     * @throws \Exception
     * @return void
     */ 
    public function validate($product)
    {
        $subCredentials = [ 'baseUrl', 'targe', 'currency', 'ApiSecret', 'PrimaryKey', 'UserId'];
        
        
        $missingCredentials = [];
        foreach ($subCredentials as $subCredential) {
            /**
             * Eg. For collections, check if the following is set 
             * - $this->collectionApiSecret, 
             * - $this->collectionPrimaryKey, and 
             * - $this->collectionUserId,
             * */  
            if (is_null($this->{$product . $subCredential}) ) {
                $missingCredentials[] = $product . $subCredential ;
            }
        }

        if( ! empty($missingCredentials)){
            throw new \Exception("The followig credentials are missing: " . implode(', ', $missingCredentials) ); 
        }
        
    }

    /**
     * Get set of configs required according to the product being used.
     *
     * @param string $product
     * @return array
     */
    protected function requiredConfigs($product)
    {
        # code...
    }
}
