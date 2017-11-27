<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 27/11/2017
 * Time: 05:54 PM
 */

namespace Sufel\App\Service;

/**
 * Class CryptoService
 * @package Sufel\App\Service
 */
class CryptoService
{
    const IV = '5883d65c65h9776b';
    const METHOD = 'AES-256-CBC';
    /**
     * @var string
     */
    private $key;

    /**
     * CryptoService constructor.
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt($data)
    {
        $output = openssl_encrypt($data, self::METHOD, $this->key, 0, self::IV);
        $output = base64_encode($output);
        return $output;
    }

    /**
     * @param string $data
     * @return string|bool
     */
    public function decrypt($data)
    {
        return openssl_decrypt(base64_decode($data), self::METHOD, $this->key, 0, self::IV);
    }
}