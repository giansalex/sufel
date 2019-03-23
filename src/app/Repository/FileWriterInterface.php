<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 19/04/2018
 * Time: 21:47
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Document;

/**
 * Interface FileWriterInterface.
 */
interface FileWriterInterface
{
    /**
     * Save files (xml, pdf).
     *
     * @param Document $document
     * @param array $files
     *
     * @return string
     */
    public function save(Document $document, array $files);
}