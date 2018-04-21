<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:45.
 */

namespace Sufel\App\Models;

/**
 * Class ApiResult.
 */
class ApiResult
{
    /**
     * Http code result.
     *
     * @var int
     */
    private $statusCode;
    /**
     * Extra Headers in response.
     *
     * @var array
     */
    private $headers;
    /**
     * Data Result or message error.
     *
     * @var array
     */
    private $data;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
