<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 27/08/2017
 * Time: 20:02
 */

namespace Tests\App\Utils;

/**
 * Trait XmlUtilsTrait
 * @package Tests\Sufel\App\Utils
 */
trait XmlUtilsTrait
{
    /**
     * @param string $filename
     * @return \DOMDocument
     */
    public function loadFromFile($filename)
    {
        $doc = new \DOMDocument();
        $doc->load($filename);

        return $doc;
    }
}