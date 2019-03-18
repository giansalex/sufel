<?php
/**
 * Created by PhpStorm.
 * User: LPALQUILER-11
 * Date: 18/04/2018
 * Time: 10:09.
 */

namespace Sufel\App\Repository;

use Sufel\App\Models\Document;
use Sufel\App\ViewModels\DocumentLogin;

/**
 * Interface DocumentRepositoryInterface.
 */
interface DocumentRepositoryInterface
{
    /**
     * Return document's id or FALSE on failure.
     *
     * @param DocumentLogin $info
     *
     * @return bool|int
     */
    public function isAuthorized(DocumentLogin $info);

    /**
     * Return id if document exist.
     *
     * @param Document $document
     *
     * @return integer|bool Id or FALSE
     */
    public function getId(Document $document);

    /**
     * Return storage ID if document exist.
     *
     * @param Document $document
     *
     * @return integer|bool Id or FALSE
     */
    public function getStorageId(Document $document);

    /**
     * Add a new document.
     *
     * @param Document $document
     *
     * @return bool|string
     */
    public function add(Document $document);

    /**
     * @param integer $id
     * @param string $storageId
     *
     * @return bool
     */
    public function setStorageId($id, $storageId);

    /**
     * Get Document By id.
     *
     * @param int $id
     *
     * @return array
     */
    public function get($id);

    /**
     * Marca un documento como anulado.
     *
     * @param Document $document
     *
     * @return bool
     */
    public function anular(Document $document);

    /**
     * @param $ruc
     * @param \DateTime $init
     * @param \DateTime $end
     *
     * @return array
     */
    public function getList($ruc, \DateTime $init, \DateTime $end);
}
