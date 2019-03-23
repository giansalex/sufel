<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 22:18.
 */

namespace Sufel\App\Service;

use Sufel\App\Models\Client;
use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\ClientProfileRepositoryInterface;
use Sufel\App\ViewModels\ClientRegister;

/**
 * Class AuthClient.
 */
class AuthClient
{
    /**
     * @var ClienteRepositoryInterface
     */
    private $repository;
    /**
     * @var ClientProfileRepositoryInterface
     */
    private $profile;
    /**
     * @var UserValidateInterface
     */
    private $validator;

    /**
     * AuthClient constructor.
     *
     * @param ClienteRepositoryInterface $repository
     * @param ClientProfileRepositoryInterface $profile
     * @param UserValidateInterface $validator
     */
    public function __construct(
        ClienteRepositoryInterface $repository,
        ClientProfileRepositoryInterface $profile,
        UserValidateInterface $validator
    ) {
        $this->repository = $repository;
        $this->profile = $profile;
        $this->validator = $validator;
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
        if ($client->getPassword() !== $client->getRepeatPassword()) {
            return [false, 'Las contraseñas no coinciden'];
        }

        $valid = $this->validator->isValid($client->getDocumento(), $client->getUserSol());

        if (!$valid) {
            return [false, 'El usuario no coincide con el numero de documento proporcionado'];
        }

        $exist = $this->repository->get($client->getDocumento());

        $newClient = new Client();
        $newClient->setDocument($client->getDocumento())
            ->setNames($client->getNombres())
            ->setPlainPassword($client->getPassword());

        $success = is_null($exist)
            ? $this->repository->add($newClient)
            : $this->repository->update($newClient);

        return [$success, ''];
    }

    /**
     * Login client.
     *
     * @param string $document
     * @param string $password
     *
     * @return array
     */
    public function login($document, $password)
    {
        $exist = $this->repository->get($document);

        if (is_null($exist)) {
            return [false, 'Cliente no se encuentra registrado'];
        }

        $success = password_verify($password, $exist->getPassword());
        if ($success === false) {
            return [false, 'Credenciales inválidas'];
        }
        $this->profile->updateAccess($document);

        return [$success, ''];
    }
}
