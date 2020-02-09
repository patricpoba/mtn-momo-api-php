<?php

namespace PatricPoba\MtnMomo\Http;

interface HttpClientInterface
{
    /**
     * @param string  $method  The HTTP method being used
     * @param string  $absoluteUrl  The URL being requested, including domain and protocol
     * @param array   $params  KV pairs for parameters. Can be nested for arrays and hashes 
     * @param array   $headers Headers to be used in the request (full strings, not KV pairs)
     * 
     * @throws \PatricPoba\MtnMomo\MtnMomoException
     * @return array An array whose first element is raw request body, second
     *    element is HTTP status code and third array of HTTP headers.
     */
    public function request($method, $absoluteUrl, $params = [], $headers = []); 
}
