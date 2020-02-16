<?php

namespace Patricpoba\MtnMomo\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PatricPoba\MtnMomo\MtnMomo;
use PHPUnit\Framework\TestCase;
use PatricPoba\MtnMomo\MtnConfig;
use GuzzleHttp\Handler\MockHandler;

class MtnMomoTest extends TestCase 
{
    protected $config;

    protected $client;


    public function setUp() 
    {
        // create the first request to check we can connect, can be added to
        // the mocks for any test that wants it
        // $couchdb1 = '{"couchdb":"Welcome","version":"2.0.0","vendor":{"name":"The Apache Software Foundation"}}';
        // $this->db_response = new Response(200, [], $couchdb1);

        // // offer a use_response for when selecting this database
        // $egdb1 = '{"db_name":"egdb","update_seq":"0-g1AAAABXeJzLYWBgYMpgTmEQTM4vTc5ISXLIyU9OzMnILy7JAUklMiTV____PyuRAY-iPBYgydAApP5D1GYBAJmvHGw","sizes":{"file":8488,"external":0,"active":0},"purge_seq":0,"other":{"data_size":0},"doc_del_count":0,"doc_count":0,"disk_size":8488,"disk_format_version":6,"data_size":0,"compact_running":false,"instance_start_time":"0"}';
        // $this->use_response = new Response(200, [], $egdb1);

        // $create = '{"ok":true,"id":"abcde12345","rev":"1-928ec193918889e122e7ad45cfd88e47"}';
        // $this->create_response = new Response(201, [], $create);

        // $fetch = '{"_id":"abcde12345","_rev":"1-928ec193918889e122e7ad45cfd88e47","noise":"howl"}';
        // $this->fetch_response = new Response(200, [], $fetch);

        // $getTokenResponse = '{"access_token": "eyJ0JKV1QiLCJhbGciOiJSMjU2In0...","token_type": "access_token", "expires_in": 3600}';
        // $this->get_token_response = new Response(200, [], $getTokenResponse);

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
    public function can_get_api_token()
    {
        $this->mockHandler->append(
            new Response(200, [], '{"access_token": "eyJ0JKV1Q2In0...","token_type": "access_token", "expires_in": 3600}')
        );
         
        $mtnMomo = new MtnMomo($this->config,  $this->client);
         
        $this->assertSame('eyJ0JKV1Q2In0...', $mtnMomo->getToken());
    }


}
