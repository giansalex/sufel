<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 31/03/2018
 * Time: 21:44.
 */

namespace Sufel\App\ViewModels;

/**
 * Class ClientRegister.
 */
class ClientRegister
{
    /**
     * @var string
     */
    private $documento;
    /**
     * @var string
     */
    private $nombres;
    /**
     * @var string
     */
    private $userSol;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $repeatPassword;

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param string $documento
     *
     * @return ClientRegister
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param string $nombres
     *
     * @return ClientRegister
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserSol()
    {
        return $this->userSol;
    }

    /**
     * @param string $userSol
     *
     * @return ClientRegister
     */
    public function setUserSol($userSol)
    {
        $this->userSol = $userSol;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return ClientRegister
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepeatPassword()
    {
        return $this->repeatPassword;
    }

    /**
     * @param string $repeatPassword
     *
     * @return ClientRegister
     */
    public function setRepeatPassword($repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;

        return $this;
    }
}
