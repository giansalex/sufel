<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 24/11/2017
 * Time: 01:07 PM
 */

namespace Sufel\App\Utils;

use Monolog\Logger;

/**
 * Class PdoErrorLogger
 * @package Sufel\App\Utils
 */
class PdoErrorLogger
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * PdoErrorLogger constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function err(\PDOStatement $statement)
    {
        $message = sprintf('PDO code: %s | data: %s',
            $statement->errorCode(),
            json_encode($statement->errorInfo()));

        $this->logger->err($message);
    }
}