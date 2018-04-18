<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:32.
 */

namespace Sufel\App\Service;

/**
 * Interface RouterBuilderInterface.
 */
interface RouterBuilderInterface
{
    /**
     * @param string $hash
     * @param string $type
     *
     * @return string
     */
    public function getFullPath($hash, $type);
}
