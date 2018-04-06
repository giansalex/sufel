<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 23:29.
 */

namespace Sufel\App\Service;

use Sufel\App\Repository\ClienteRepository;
use Sufel\App\Repository\ClientProfileRepository;

/**
 * Class ClientProfile.
 */
class ClientProfile
{
    /**
     * @var ClienteRepository
     */
    private $client;
    /**
     * @var ClientProfileRepository
     */
    private $profile;

    /**
     * AuthClient constructor.
     *
     * @param ClienteRepository $clienteRepository
     * @param ClientProfileRepository $profileRepository
     */
    public function __construct(ClienteRepository $clienteRepository, ClientProfileRepository $profileRepository)
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
     * @param string $repeatNew
     *
     * @return array
     */
    public function changePassword($document, $old, $new, $repeatNew)
    {
        if ($new !== $repeatNew) {
            return [false, 'Las contraseñas no coinciden'];
        }

        $client = $this->client->get($document);

        if (!password_verify($old, $client->getPassword())) {
            return [false, 'La contraseña original no es la correcta'];
        }

        $success = $this->profile->updatePassword($document, $new);

        return [$success, ''];
    }
}
