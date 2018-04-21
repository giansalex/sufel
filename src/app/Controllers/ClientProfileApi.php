<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 23:37.
 */

namespace Sufel\App\Controllers;

use Sufel\App\Models\ApiResult;
use Sufel\App\Service\ClientProfile;

/**
 * Class ClientProfileApi.
 */
class ClientProfileApi implements ClientProfileApiInterface
{
    use ResponseTrait;

    /**
     * @var ClientProfile
     */
    private $profile;

    /**
     * ClientProfileApi constructor.
     *
     * @param ClientProfile $profile
     */
    public function __construct(ClientProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Change client password.
     *
     * @param string $document
     * @param string $old
     * @param string $new
     * @param string $repeat
     *
     * @return ApiResult
     */
    public function changePassword($document, $old, $new, $repeat)
    {
        list($success, $message) = $this->profile->changePassword(
            $document,
            $old,
            $new,
            $repeat
        );

        if ($success === false) {
            return $this->response(400, ['message' => empty($message) ? 'No se pudo cambiar la contraseña' : $message]);
        }

        return $this->ok(['message' => 'contraseña cambiada']);
    }
}
