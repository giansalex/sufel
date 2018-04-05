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
     * Une los campos y sus valores, luego une sus partes.
     *
     * @param array $parts
     * @param string $glue
     *
     * @return string
     */
    public function joinAll(array $parts, $glue = ' AND ')
    {
        $items = [];
        foreach ($parts as $column => $value) {
            if (empty($value)) {
                continue;
            }

            $items[] = $column . "='" . $value . "'";
        }

        return $this->joinParts($items, $glue);
    }

    /**
     * Junta los elmentos que forman parte de un query.
     *
     * @param array $parts
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
