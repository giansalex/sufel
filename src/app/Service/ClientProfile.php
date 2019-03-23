<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 23:29.
 */

namespace Sufel\App\Service;

use Sufel\App\Repository\ClienteRepositoryInterface;
use Sufel\App\Repository\ClientProfileRepositoryInterface;

/**
 * Class ClientProfile.
 */
class ClientProfile
{
    /**
     * @var ClienteRepositoryInterface
     */
    private $client;
    /**
     * @var ClientProfileRepositoryInterface
     */
    private $profile;

    /**
     * AuthClient constructor.
     *
     * @param ClienteRepositoryInterface $clienteRepository
     * @param ClientProfileRepositoryInterface $profileRepository
     */
    public function __construct(
        ClienteRepositoryInterface $clienteRepository,
        ClientProfileRepositoryInterface $profileRepository)
    {
        $this->client = $clienteRepository;
        $this->profile = $profileRepository;
    }

    /**
     * Change password of Client.
     *
     * @param string $document
     * @param string $old
     * @param string $new
     *
     * @return array
     */
    public function changePassword($document, $old, $new)
    {
        $client = $this->client->get($document);

        if (is_null($client)) {
            return [false, 'Usuario no fue encontrado'];
        }

        if (!password_verify($old, $client->getPassword())) {
            return [false, 'La contraseña original no es la correcta'];
        }

        $success = $this->profile->updatePassword($document, $new);

        return [$success, ''];
    }
}
