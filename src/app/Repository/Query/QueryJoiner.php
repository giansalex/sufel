<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 04/04/2018
 * Time: 22:01.
 */

namespace Sufel\App\Repository\Query;

/**
 * Class QueryJoiner.
 */
class QueryJoiner
{
    /**
     * @var array
     */
    private $params;

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Une los campos y sus valores, luego une sus partes.
     *
     * @param array  $parts
     * @param string $glue
     *
     * @return string
     */
    public function joinAll(array $parts, $glue = ' AND ')
    {
        $items = [];
        $this->params = [];
        foreach ($parts as $column => $value) {
            if (empty($value)) {
                continue;
            }
            $key = ':'.$column;
            $items[] = $column.' = '.$key;

            $this->params[$key] = $value;
        }

        return $this->joinParts($items, $glue);
    }

    /**
     * Junta los elmentos que forman parte de un query.
     *
     * @param array  $parts
     * @param string $glue
     *
     * @return string
     */
    public function joinParts(array $parts, $glue = ' AND ')
    {
        $items = [];
        foreach ($parts as $item) {
            if (empty($item)) {
                continue;
            }

            $items[] = $item;
        }

        return join($glue, $items);
    }
}
