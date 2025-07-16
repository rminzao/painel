<?php

namespace Core\Routing;

class Response
{
    /**
     * Status http code
     *
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Response header
     *
     * @var array
     */
    private $headers = [];

    /**
     * Content type return
     *
     * @var array
     */
    private $contentType = 'text/html';

    /**
     * Content response
     *
     * @var mixed
     */
    private $content;

    /**
     * Method define values response
     *
     * @param integer $httpCode
     * @param mixed   $content
     * @param string  $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Change contentType response
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Add register on header
     *
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Send headers to browser
     */
    private function sendHeaders()
    {
        http_response_code((int)$this->httpCode);
        $this->protectedHeaders();
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    /**
     * It prevents clickjacking attacks, sniffing attacks, and XSS attacks.
     */
    protected function protectedHeaders()
    {
        header("X-Frame-Options: SAMEORIGIN"); // prevent clickjacking attacks
        header("Content-Security-Policy: frame-ancestors 'none'"); // prevent clickjacking attacks
        header("X-Content-Type-Options: nosniff"); // prevent sniffing attacks
        header("X-XSS-Protection: 1; mode=block"); // prevent XSS attacks
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains"); // prevent XSS attacks
    }

    /**
     * Send response to user
     *
     * @param string $key
     * @param string $value
     */
    public function sendResponse()
    {
        $this->sendHeaders();
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/xml':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }
}
