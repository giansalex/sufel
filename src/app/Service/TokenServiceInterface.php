<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 17/03/2019
 * Time: 22:09.
 */

namespace Sufel\App\Service;

interface TokenServiceInterface
{
    /**
     * Generate token.
     *
     * @param array $data
     * @param string $secret
     *
     * @return string
     */
    public function create($data, $secret);
}
