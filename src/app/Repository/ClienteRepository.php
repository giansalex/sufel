<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 12:34.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Client;

/**
 * Class ClienteRepository.
 */
class ClienteRepository implements ClienteRepositoryInterface
{
    /**
     * @var DbConnection
     */
    private $db;

    /**
     * ClienteRepository constructor.
     *
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        $this->db = $dbConnection;
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
     * Update existing client.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function update(Client $client)
    {
        $params = [
            $client->getNames(),
            $client->getPassword(),
            $client->getDocument(),
        ];
        $query = 'UPDATE client SET nombres=?, password=? WHERE documento=?';

        return $this->db->exec($query, $params);
    }

    /**
     * @param string $document
     *
     * @return Client|null
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

    /**
     * Lista empresas que emitieron comprobante al receptor dado.
     *
     * @param string $receptor
     *
     * @return array
     */
    public function getCompanies($receptor)
    {
        $query = <<<SQL
SELECT c.ruc, c.nombre FROM company c
WHERE c.ruc IN (SELECT DISTINCT(d.emisor) FROM document d WHERE d.cliente_doc = ?)
SQL;

        $rows = $this->db
            ->fetchAll($query, [$receptor]);

        return $rows;
    }
}
