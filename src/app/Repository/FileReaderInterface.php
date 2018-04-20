<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:50.
 */

namespace Sufel\App\Repository;

/**
 * Interface FileRepositoryInterface.
 */
interface FileReaderInterface
{
    /**
     * Get file in specify format.
     *
     * @param string|int $id
     * @param string     $type Options: xml|pdf|cdr
     *
     * @return string
     */
    public function getFile($id, $type);
}
