<?php

namespace PatricPoba\MtnMomo\Http;

use PatricPoba\MtnMomo\Utilities\AttributesMassAssignable;
  

class ApiResponse
{
    use AttributesMassAssignable;
    
    protected $headers;
    protected $content;
    protected $statusCode;

    /**
     * Construct object
     *
     * @param int $statusCode
     * @param string $content
     * @param array $headers
     */
    public function __construct($statusCode, $content, $headers = array())
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->content = $content;

        /**
         * Dynamically create class variables from content array, so content can be accessed directly.
         * eg $response->category, $response->user_id for ['category'=> 'Electronics','user_id'=> 4]
         * $this->content must be set before calling $this->massAssignAttributes()
         */
        $this->massAssignAttributes($this->toArray());
    }

    /**
     * Get array format of api response
     * @return array
     */
    public function toArray()
    {
        return \json_decode($this->content, true);
    }

    /**
     * Get json format of api response
     * @return string
     */
    public function toJson()
    {
        return $this->content;
    }

    /**
     * @return numeric
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * return bool
     */
    public function isSuccess() : bool
    {
        return $this->getStatusCode() < 400;
    }
 

    public function __toString()
    {
        return '[Response] HTTP ' . $this->getStatusCode() . ' ' . $this->content;
    }
}
