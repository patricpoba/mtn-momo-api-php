<?php


namespace PatricPoba\MtnMomo\Http; 

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\ClientInterface; 
use PatricPoba\MtnMomo\MtnMomoException;
use GuzzleHttp\Exception\BadResponseException;

final class GuzzleClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function request(
        $method,
        $url,
        $params = [],
        $data = [],
        $headers = [],
        $user = null,
        $password = null,
        $timeout = null
    ) {
        try {
            $response = $this->client->send(new Request($method, $url, $headers), [
                'timeout' => $timeout,
                'auth' => [$user, $password],
                'query' => $params,
                'form_params' => $data,
            ]);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();

        } catch (\Exception $exception) {
            throw new MtnMomoException('Unable to complete the HTTP request', 0, $exception);
        }

        // Casting the body (stream) to a string performs a rewind, ensuring we return the entire response.
        // See https://stackoverflow.com/a/30549372/86696
        return new Response($response->getStatusCode(), (string) $response->getBody(), $response->getHeaders());
    }
}
