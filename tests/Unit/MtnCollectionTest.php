<?php

namespace Patricpoba\MtnMomo\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response; 
use PHPUnit\Framework\TestCase;
use PatricPoba\MtnMomo\MtnConfig;
use GuzzleHttp\Handler\MockHandler;
use PatricPoba\MtnMomo\MtnCollection;

class MtnCollectionTest extends TestCase 
{
    protected $config;

    protected $client;


    public function setUp() 
    { 
        $this->config = new MtnConfig([
            'baseUrl'              => 'https://sandbox.momodeveloper.mtn.com',
            'currency'              => 'EUR',
            'targetEnvironment'     => 'sandbox',
            "collectionApiSecret"   => '3463953c31064e6e8ae634cd94f13c8c',
            "collectionPrimaryKey"  => 'aadb6f286e95415db9024c7a4e2c6025',
            "collectionUserId"      => 'b4d4019f-8617-4843-a4b8-ed90941747a3'
        ]);
        
        $this->mockHandler = new MockHandler(); 

        $this->client = new Client(['handler' => $this->mockHandler ]);
    }

    /** @test */
	public function package_version_is_one_point_one()
	{ 
		return $this->assertSame(MtnCollection::VERSION, '1.0');
	}

    /** @test */
    public function can_get_api_token()
    {
        $mockResponse = ['access_token'=> 'eyJ0JKV1Q2In0...', 'token_type'=> 'access_token', 'expires_in'=> 3600];

        $this->mockHandler->append(
            new Response(200, [],  json_encode($mockResponse))
        );
         
        $mtnMomo = new MtnCollection($this->config,  $this->client);

        // $mtnMomo = new class($this->config,  $this->client) extends MtnMomo {} ;
         
        $this->assertSame($mockResponse['access_token'], $mtnMomo->getToken());
    }


}
