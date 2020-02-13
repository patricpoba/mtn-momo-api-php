<?php
 
namespace PatricPoba\MtnMomo\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\ClientInterface; 
use PatricPoba\MtnMomo\Exceptions\MtnMomoException;

class GuzzleClient implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Instance of this HttpCLient (Singleton Pattern)
     *
     * @var [type]
     */
    private static $instance;


    public function __construct(ClientInterface $client = null)
    {
        if ($client) {
            $this->client = $client;
        }
        $this->client = new Client(); 
    }
 
    /**
     * Make an http request
     *
     * @param [type] $method
     * @param [type] $url
     * @param array $params
     * @param array $headers
     * @return \PatricPoba\MtnMomo\Http\Response
     */
    public function request($method, $url, $params = [], $headers = [])
    {
        try {
            $response = $this->client->send(
                new Request($method, $url, $headers),
                ['query' => $params, 'json' => $params]
            );
        } catch (\Exception $exception) {
            throw new MtnMomoException('HTTP request failed: ' . $url, 0, $exception);
        }

        // Casting the body (stream) to a string performs a rewind, ensuring we return the entire response.
        // See https://stackoverflow.com/a/30549372/86696
        return new ApiResponse($response->getStatusCode(), (string) $response->getBody(), $response->getHeaders());
    }

    
    // public static function instance()
    // {
    //     if (!self::$instance) {
    //         self::$instance = new self();
    //     }
    //     return self::$instance;
    // }
}
