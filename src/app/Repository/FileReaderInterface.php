<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:50.
 */

namespace Sufel\App\Repository;

/**
 * Interface FileReaderInterface.
 */
interface FileReaderInterface
{
    /**
     * Get file in specify format.
     *
     * @param string $id
     * @param string $type Options: xml,pdf,cdr,etc.
     *
     * @return string
     */
    public function read($id, $type);
}
