<?php

namespace PatricPoba\MtnMomo\Http;
  

class Response
{
    protected $headers;
    protected $content;
    protected $statusCode;

    /**
     * Undocumented function
     *
     * @param numeric $statusCode
     * @param string $content
     * @param array $headers
     */
    public function __construct($statusCode, $content, $headers = array())
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return \json_decode($this->content, true);
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function ok()
    {
        return $this->getStatusCode() < 400;
    }

    public function __toString()
    {
        return '[Response] HTTP ' . $this->getStatusCode() . ' ' . $this->content;
    }
}
