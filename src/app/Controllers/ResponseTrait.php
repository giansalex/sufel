<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 19/04/2018
 * Time: 17:38.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;

/**
 * Trait ResponseTrait.
 */
trait ResponseTrait
{
    /**
     * @param array $data
     * @return ApiResult
     */
    protected function ok(array $data)
    {
        return $this->response(200, $data);
    }

    /**
     * @param int $code
     * @param array $data
     * @param array $headers
     * @return ApiResult
     */
    protected function response($code, array $data = [], array $headers = [])
    {
        $response = new ApiResult();
        $response->setStatusCode($code)
            ->setHeaders($headers)
            ->setData($data);

        return $response;
    }
}
