<?php

class LastRequest
{
    /**
     * @var string
     */
    private $method, $url;
    /**
     * @var array|object|null
     */
    private $body;

    /**
     * @param string $method
     * @param string $url
     * @param array|object|null $body
     */
    public function __construct($method, $url, $body)
    {
        $this->method = $method;
        $this->url = $url;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array|object|null
     */
    public function getBody()
    {
        return $this->body;
    }
}