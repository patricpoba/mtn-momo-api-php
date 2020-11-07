<?php
 
namespace PatricPoba\MtnMomo\Http;

use GuzzleHttp\Client; 
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
        // $this->client = $client ?? new Client();  
        $this->client = $client ?? new Client(['http_errors' => false]);
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
            // $response = $this->client->send(
            //     new \GuzzleHttp\Psr7\Request\Request($method, $url, $headers), ['json' => $params]
            // );

            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'json' => $params
            ]);
 
        } catch (\Exception $exception) {
            throw new MtnMomoException("HTTP request failed: {$url} " . $exception->getMessage(), null, $exception); 
        }

        // Casting the body (stream) to a string performs a rewind, ensuring we return the entire response.
        // See https://stackoverflow.com/a/30549372/86696
        return new ApiResponse(
            $response->getStatusCode(), 
            (string) $response->getBody(), 
            $response->getHeaders(), 
            [
                'headers'     => $headers,
                'form_data'   => $params,
                'url'         => $url,
                'method'      => strtoupper($method)
            ]
        );
    }
 
}
