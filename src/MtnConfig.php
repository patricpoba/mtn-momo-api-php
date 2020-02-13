<?php

namespace PatricPoba\MtnMomo;
 
use PatricPoba\MtnMomo\Exceptions\CredentialsNotSetException;
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
     * Set values of configs
     *
     * @param array $configs
     * @return void
     */
    public function __construct($configArray)
    {
        $this->massAssignAttributes($configArray);
    }

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
        $missingCredentials = [];
         
        foreach ($this->requiredConfigs($product) as $config) {
           
            if (is_null($this->{$config}) ) {
                $missingCredentials[] = $config ;
            }
        }

        if( ! empty($missingCredentials)){
            throw new CredentialsNotSetException("The followig credentials are missing: " . implode(', ', $missingCredentials) ); 
        } 

        return $this;
    }

    /**
     * Get set of configs required according to the product being used.
     *
     * @param string $product disbursement | remittance | collection 
     * @return array
     */
    protected function requiredConfigs($product)
    { 
        return [ 
            'baseUrl', 
            'currency', 
            'targetEnvironment', 
            $product . 'ApiSecret', 
            $product . 'PrimaryKey', 
            $product . 'UserId'
        ];
    }
}
