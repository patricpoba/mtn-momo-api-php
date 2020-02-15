<?php

namespace Patricpoba\MtnMomo\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PatricPoba\MtnMomo\MtnConfig; 
use PatricPoba\MtnMomo\Exceptions\CredentialsNotSetException;
use PatricPoba\MtnMomo\Exceptions\WrongProductException;

class MtnConfigTest extends TestCase
{
     
    public function test_config_throws_exception_if_any_config_is_missing()
    {
        $this->expectException(CredentialsNotSetException::class); 
        
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
        $config = new MtnConfig([
            'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com/', 
            'currency'              => 'GHS', 
            'targetEnvironment'     => 'sandbox', 
            "collectionApiSecret"  => substr(md5(mt_rand()), 0, 9), 
            "collectionPrimaryKey" => substr(md5(mt_rand()), 0, 9), 
            "collectionUserId"     => substr(md5(mt_rand()), 0, 9)
       ]);
          
        $this->assertSame( $config->validate('collection'), $config);
    }


    public function test_config_can_be_instantiated_without_errors_with_valid_arguments()
    {  
       $product = 'collection';

       $configArray = [
            'baseUrl'               => 'https://sandbox.momodeveloper.mtn.com/', 
            'currency'              => 'GHS', 
            'targetEnvironment'     => 'sandbox', 
            "{$product}ApiSecret"  => substr(md5(mt_rand()), 0, 9), 
            "{$product}PrimaryKey" => substr(md5(mt_rand()), 0, 9), 
            "{$product}UserId"     => substr(md5(mt_rand()), 0, 9)
       ];

        $config = new MtnConfig($configArray);
        
        $config->validate($product); 

        $this->assertSame($config->baseUrl                  , $configArray['baseUrl']);
        $this->assertSame($config->currency                 , $configArray['currency']);
        $this->assertSame($config->targetEnvironment        , $configArray['targetEnvironment']);
        $this->assertSame($config->{"{$product}ApiSecret"}  , $configArray["{$product}ApiSecret"]);
        $this->assertSame($config->{"{$product}PrimaryKey"} , $configArray["{$product}PrimaryKey"]);
        $this->assertSame($config->{"{$product}UserId"}     , $configArray["{$product}UserId"]);
    }
 
}
