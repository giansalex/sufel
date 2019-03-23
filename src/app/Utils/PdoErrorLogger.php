<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 24/11/2017
 * Time: 01:07 PM
 */

namespace Sufel\App\Utils;

use Psr\Log\LoggerInterface;

/**
 * Class PdoErrorLogger
 * @package Sufel\App\Utils
 */
class PdoErrorLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PdoErrorLogger constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \PDOStatement $statement
     */
    public function err(\PDOStatement $statement)
    {
        $this->logger
            ->error('PDO code:' . $statement->errorCode(), $statement->errorInfo());
    }
}