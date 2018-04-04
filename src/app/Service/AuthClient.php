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
use Sufel\App\Repository\ClientProfileRepository;
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
     * @var ClientProfileRepository
     */
    private $profile;
    /**
     * @var UserValidateInterface
     */
    private $validator;

    /**
     * AuthClient constructor.
     *
     * @param ClienteRepository $repository
     * @param ClientProfileRepository $profile
     * @param UserValidateInterface $validator
     */
    public function __construct(
        ClienteRepository $repository,
        ClientProfileRepository $profile,
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

        $success = $exist
            ? $this->repository->update($newClient)
            : $this->repository->add($newClient);

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

        $success = password_verify($password, $exist->getPassword());
        if ($success === false) {
            return [false, 'Credenciales inválidas'];
        }
        $this->profile->updateAccess($document);

        return [$success, ''];
    }
}
