<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 12:34.
 */

namespace Sufel\App\Repository;

use Psr\Container\ContainerInterface;
use Sufel\App\Models\Client;

/**
 * Class ClienteRepository.
 */
class ClienteRepository
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
     * Add new client.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function add(Client $client)
    {
        $params = [
            $client->getDocument(),
            $client->getNames(),
            $client->getPassword(),
        ];
        $query = 'INSERT INTO client(documento, nombres, password) VALUES(?, ?, ?)';

        return $this->db->exec($query, $params);
    }

    /**
     * @param string $document
     *
     * @return Client
     */
    public function get($document)
    {
        $con = $this->db->getConnection();

        $stm = $con->prepare('SELECT nombres, password, created, last_access FROM client WHERE documento = ?');
        $stm->execute([$document]);

        if ($stm->rowCount() === 0) {
            return null;
        }

        $row = $stm->fetch(\PDO::FETCH_ASSOC);

        $client = new Client();
        $client->setDocument($document)
            ->setNames($row['nombres'])
            ->setPassword($row['password'])
            ->setCreated(new \DateTime($row['created']))
            ->setLast(empty($row['last_access']) ? null : new \DateTime($row['last_access']));

        return $client;
    }
}
