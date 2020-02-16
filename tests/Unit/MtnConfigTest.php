<?php

namespace Patricpoba\MtnMomo\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PatricPoba\MtnMomo\MtnConfig; 
use PatricPoba\MtnMomo\Exceptions\MtnConfigException;
use PatricPoba\MtnMomo\Exceptions\WrongProductException;

class MtnConfigTest extends TestCase
{
    protected $configArray;


    public function setUp()
    { 
        $this->configArray = [
            'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com',
            'currency'              => 'EUR',
            'targetEnvironment'     => 'sandbox',
            "collectionApiSecret"  => substr(md5(mt_rand()), 0, 9),
            "collectionPrimaryKey" => substr(md5(mt_rand()), 0, 9),
            "collectionUserId"     => substr(md5(mt_rand()), 0, 9)
       ];
    }

    /** @test */   
    public function config_throws_exception_if_any_config_is_missing()
    {
        $this->expectException(MtnConfigException::class); 
        
        $config = new MtnConfig([]);
        
        $config->validate('collection'); 
    }
 
    /** @test */ 
    public function config_throws_exception_if_product_is_wrong()
    {
        $this->expectException(WrongProductException::class); 
         
        $config = new MtnConfig([]);
        
        $config->validate('wrongProduct'); 
    }
 
    /** @test */ 
    public function config_validates_without_exception_if_product_and_its_credentials_are_set()
    {    
        $config = new MtnConfig($this->configArray);

        $this->assertSame($config, $config->validate('collection'));
    }

    /** @test */ 
    public function config_can_be_instantiated_without_errors_with_valid_arguments()
    {  
       $product = 'collection';
 
        $config = new MtnConfig($this->configArray);
        
        $config->validate($product); 

        $this->assertSame( $this->configArray['baseUrl']              , $config->baseUrl);
        $this->assertSame( $this->configArray['currency']             , $config->currency);
        $this->assertSame( $this->configArray['targetEnvironment']    , $config->targetEnvironment);
        $this->assertSame( $this->configArray["{$product}ApiSecret"]  , $config->{"{$product}ApiSecret"});
        $this->assertSame( $this->configArray["{$product}PrimaryKey"] , $config->{"{$product}PrimaryKey"});
        $this->assertSame( $this->configArray["{$product}UserId"]     , $config->{"{$product}UserId"});
    }

    /** @test */
    public function get_value_returns_config_value()
    { 
        $config = new MtnConfig($this->configArray);

        $this->assertSame($this->configArray['collectionPrimaryKey'] , $config->getValue('collection', 'PrimaryKey') );
        $this->assertSame($this->configArray['collectionApiSecret']  , $config->getValue('collection', 'ApiSecret') ); 
        $this->assertSame($this->configArray['collectionUserId']     , $config->getValue('collection', 'UserId') );
    }

    /** @test */
    public function get_value_function_throws_exception_if_params_are_wrong()
    {
        $this->expectException(MtnConfigException::class);
        
        $config = new MtnConfig($this->configArray); 
        
        $config->getValue('collection', 'wrongProductConfig');
    }
 
}
