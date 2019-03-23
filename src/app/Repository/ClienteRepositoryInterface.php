<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:02.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Client;

/**
 * Interface ClienteRepositoryInterface.
 */
interface ClienteRepositoryInterface
{
    /**
     * Add new client.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function add(Client $client);

    /**
     * Update existing client.
     *
     * @param Client $client
     *
     * @return bool
     */
    public function update(Client $client);

    /**
     * @param string $document
     *
     * @return Client|null
     */
    public function get($document);

    /**
     * Lista empresas que emitieron comprobante al receptor dado.
     *
     * @param string $receptor
     *
     * @return array
     */
    public function getCompanies($receptor);
}
