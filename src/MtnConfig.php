<?php

namespace PatricPoba\MtnMomo;
 
use PatricPoba\MtnMomo\Exceptions\MtnConfigException;
use PatricPoba\MtnMomo\Exceptions\WrongProductException;
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
     * @var string The MTN product suscribed to.
     * collection|disbursement|remittance
     */
    public $product;

    const PRODUCTS = ['collection', 'disbursement', 'remittance'];

    /**
     * @var string The MTN OpenApi collections credentials
     *  - API Secret.
     *  - Primary Key
     *  - User Id
     *  - callback Url : can be overriden by a url set in the params array 
     */
    public $collectionApiSecret;

    public $collectionPrimaryKey;

    public $collectionUserId;

    public $collectionCallbackUrl;
     

    /**
     * @var string The MTN OpenApi remittance credentials 
     *  - API Secret.
     *  - Primary Key
     *  - User Id
     *  - callback Url : can be overriden by a url set in the params array 
     */
    public $remittanceApiSecret;

    public $remittancePrimaryKey;

    public $remittanceUserId;

    public $remittanceCallbackUrl;
 

    /**
     * @var string The MTN OpenApi disbursements primary Key
     *  - API Secret.
     *  - Primary Key
     *  - User Id
     *  - callback Url : can be overriden by a url set in the params array 
     */
    public $disbursementApiSecret;
 
    public $disbursementPrimaryKey;
 
    public $disbursementUserId;

    public $disbursementCallbackUrl;


    /**
     * Set values of configs
     *
     * @param array $configArray
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
        if (! in_array($product, static::PRODUCTS) ) {
            throw new WrongProductException("Wrong product chosen. product must be " . implode(' or ', static::PRODUCTS));
        }

        $missingCredentials = [];
         
        foreach ($this->requiredConfigs($product) as $config) {
           
            if (is_null($this->{$config}) ) {
                $missingCredentials[] = $config ;
            }
        }

        if( ! empty($missingCredentials)){
            throw new MtnConfigException("The followig credentials are missing: " . implode(', ', $missingCredentials) ); 
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

    /**
     * Get specific config dynamicsally
     *
     * @param string $product 'collection'|'disbursement'|'remittance'
     * @param string $configKey 'primaryKey' | 'ApiSecret' | 'UserId'
     * $throws PatricPoba\MtnMomo\Exceptions\MtnConfigException\MtnConfigException
     * @return string
     */
    public function getValue($product, $configKey, $throwExceptionIfNull = false)
    {
        // if $product = 'collection' and $configKey= 'primaryKey', $configKey = 'collectionPrimaryKey' 
        $configKey = strtolower($product) . ucfirst($configKey);

       if ($throwExceptionIfNull && ! isset($this->$configKey)) {
           throw new MtnConfigException($configKey . " does not exist or is empty on this " . static::class . " instance") ;
       }

       return $this->$configKey;
    }
}
