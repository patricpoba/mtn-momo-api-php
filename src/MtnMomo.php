<?php

namespace PatricPoba\MtnMomo;

class MtnMomo
{
    /**
     * @var string the base url of the API 
     */ 
    const VERSION = '0.1';


    /**
     * @var string target environment
     */
    public static $baseUrl;


    /**
     * @var string the currency of http calls
     */
    public static $targetEnvironment;


    /**
     * @var string The MomoApi Collections API Secret.
     */
    public static $currency;

    /**
     * @var string The MomoApi collections primary Key
     */
    public static $collectionApiSecret;

    /**
     * @var string The MomoApi collections User Id
     */
    public static $collectionPrimaryKey;


    /**
     * @var string The MomoApi remittance API Secret.
     */
    public static $collectionUserId;

    /**
     * @var string The MomoApi remittance primary Key
     */
    public static $remittanceApiSecret;

    /**
     * @var string The MomoApi remittance User Id
     */
    public static $remittancePrimaryKey;


    /**
     * @var string The MomoApi disbursements API Secret.
     */
    public static $remittanceUserId;

    /**
     * @var string The MomoApi disbursements primary Key
     */
    public static $disbursementApiSecret;

    /**
     * @var string The MomoApi disbursements User Id
     */
    public static $disbursementPrimaryKey;


    /**
     * @var boolean Defaults to true.
     */
    public static $disbursementUserId;



    
}
