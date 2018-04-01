<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:18.
 */

namespace Sufel\App\Service;

use Sufel\App\Models\Client;
use Sufel\App\Repository\ClienteRepository;
use Sufel\App\ViewModels\ClientRegister;

/**
 * Class AuthClient.
 */
class AuthClient
{
    /**
     * @var ClienteRepository
     */
    private $repository;

    /**
     * AuthClient constructor.
     *
     * @param ClienteRepository $repository
     */
    public function __construct(ClienteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Register a new client.
     *
     * @param ClientRegister $client
     *
     * @return array
     */
    public function register(ClientRegister $client)
    {
        $exist = $this->repository->get($client->getDocumento());

        if ($exist) {
            return [false, 'El cliente ya esta registrado'];
        }

        $newClient = new Client();
        $newClient->setDocument($client->getDocumento())
            ->setNames($client->getNombres())
            ->setPlainPassword($client->getPassword());

        $success = $this->repository->add($newClient);

        return [$success, ''];
    }

    /**
     * Login client.
     *
     * @param string $document
     * @param $password
     *
     * @return array
     */
    public function login($document, $password)
    {
        $exist = $this->repository->get($document);

        if (empty($exist)) {
            return [false, 'Cliente no se encuentra registrado'];
        }

        return [password_verify($password, $exist->getPassword()), ''];
    }
}
