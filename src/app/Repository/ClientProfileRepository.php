<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 23:24.
 */

namespace Sufel\App\Repository;

use Psr\Container\ContainerInterface;
use Sufel\App\Models\Client;

/**
 * Class ClientProfileRepository.
 */
class ClientProfileRepository implements ClientProfileRepositoryInterface
{
    /**
     * @var DbConnection
     */
    private $db;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CompanyRepository constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get(DbConnection::class);
        $this->container = $container;
    }

    /**
     * @param string $document
     * @param string $password
     *
     * @return bool
     */
    public function updatePassword($document, $password)
    {
        $client = (new Client())->setPlainPassword($password);

        $params = [
            $client->getPassword(),
            $document,
        ];

        return $this->db->exec('UPDATE client SET password = ? WHERE documento = ?', $params);
    }

    /**
     * Update client last access.
     *
     * @param string $document
     * @return bool
     */
    public function updateAccess($document)
    {
        $query = 'UPDATE client SET last_access = ? WHERE documento = ?';
        $now = new \DateTime();

        return $this->db->exec($query, [$now->format('Y-m-d H:i:s'), $document]);
    }
}
