<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 19/04/2018
 * Time: 21:47
 */

namespace Sufel\App\Repository;

/**
 * Interface FileWriterInterface.
 */
interface FileWriterInterface
{
    /**
     * Save files (xml, pdf).
     *
     * @param string|int $id
     * @param array $files
     */
    public function writeFiles($id, array $files);
}