<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 17/03/2019
 * Time: 21:58
 */

namespace Sufel\App\Models;

class DocumentConverter
{
    public function convertToDoc(array $data)
    {
        $doc = new Document();
        $doc->setEmisor($data['emisor'])
            ->setTipo($data['tipo'])
            ->setSerie($data['serie'])
            ->setCorrelativo($data['correlativo'])
            ->setFecha($data['fecha'])
            ->setTotal(floatval($data['total']))
            ->setClientTipo($data['cliente_tipo'])
            ->setClientDoc($data['cliente_doc'])
            ->setClientName($data['cliente_nombre'])
            ->setBaja($data['baja']);

        return $doc;
    }
}