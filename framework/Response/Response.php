<?php

namespace Framework\Response;

/**
 * Class Response for creating response
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Response
{
    /**
     * @var string Content to sending
     */
    private $content;
    /**
     * @var string HTTP status
     */
    private $status;
    /**
     * @var array Headers to sending
     */
    private $headers = [];

    /**
     * @var array Response statuses
     */
    public $statusTexts = array(
        200 => 'OK',
        302 => 'Redirect',
    );

    /**
     * Response class constructor
     *
     * @param string $content Content to sending
     * @param int $status HTTP status
     * @param array $headers Headers to sending
     */
    public function __construct($content = '', $status = 200, $headers = array())
    {
        $this->headers = $headers;
        $this->content = $content;
        $this->status  = $status;
    }

    /**
     * Add array of headers
     *
     * @param array $headers Headers to adding
     */
    public function setHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Add one header
     *
     * @param string $name Name of header
     * @param string $header Header to adding
     */
    public function setHeader($name, $header)
    {
        $this->headers[$name] = $header;
    }

    /**
     * Set content
     *
     * @param string $content Content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Send all
     */
    public function send()
    {
        foreach ($this->headers as $name => $values) {
            header($name . ': ' . $values);
        }

        header('HTTP/1.0 ' . $this->status . ' ' . $this->statusTexts[$this->status],
            true, $this->status);

        if ($this->content) {
            echo $this->content;
        }
    }
}