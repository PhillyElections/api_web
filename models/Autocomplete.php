<?php
/**
 * Autocomplete model.
 *
 * Autocomplete API's model
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
use PDO;

namespace models;

/**
 * Autocomplete class.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/models
 */
class Autocomplete
{
    protected $callback;
    protected $core;
    protected $criteria;
    protected $fields;
    protected $params;
    protected $table;

    public function __construct()
    {
        d('constructor of Autocomplete');
        $this->core = \lib\Core::getInstance();
        $this->table = 'api_block_range';

        // set properties from _GET
        $this->setup();
    }

    // Get all stuff
    public function fetch()
    {
        d('fetch of Autocomplete');
        $json = false;
        $sql = ' SELECT DISTINCT ' . $this->fields . ' FROM ' . $this->table . ' WHERE ' . $this->criteria . ' ORDER BY street_name LIMIT 0, 10 ';

        $stmt = $this->core->dbh->prepare($sql);
        foreach ($this->params as $key=>$pair) {
            $stmt->bindParam($key, $pair['value'], $pair['type']);
        }

        if ($results = $stmt->execute()) {
            $json = $this->callback . '(' . json_encode($results) . ')';
        } else {
            d($stmt->errorInfo(), $this, $stmt);
        }

        return $json;
    }

    protected function setup()
    {
        d('setup of Autocomplete');
        $this->callback = urldecode($_REQUEST['callback']);
        $parts = explode(' ', urldecode($_REQUEST['address']));

        if (count($parts)>4) {
            die('No thanks.  Not even touching that.');
        }

        $this->fields = 'prefix_dir, TRIM(LEADING \'0\' FROM street_name) as street, type_dir, ';

        $number = array_pop($parts);
        $street = implode('', $parts);

        if ($street) {
            d('street is truthy', $number, $street);
            $this->criteria = 'house_range_start <= :a1 AND house_range_end >= :a2 AND (CONCAT(prefix_dir, TRIM(LEADING \'0\' FROM street_name) as street, type_dir) LIKE :a3 OR (CONCAT(TRIM(LEADING \'0\' FROM street_name) as street, type_dir) LIKE :a4';
            $this->params = array(
                ':a1' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a3' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
                ':a4' => array('value'=>$street . '%','type'=>PDO::PARAM_STR),
            );
        } else {
            d('street is falsy', $number);
            $this->criteria = 'house_range_start <= :a1 AND house_range_end >= :a2';
            $this->params = array(
                ':a1' => array('value'=>$number,'type'=>PDO::PARAM_INT),
                ':a2' => array('value'=>$number,'type'=>PDO::PARAM_INT),
            );
        }
    }

    protected function titleCase($string)
    {
        $word_splitters = array(' ', '-', 'O\'', 'L\'', 'D\'', 'St.', 'Mc');
        $lowercase_exceptions = array('a', 'the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', 'l\'', 'd\'');
        $uppercase_exceptions = array('III', 'IV', 'VI', 'VII', 'VIII', 'IX');

        $string = strtolower($string);
        foreach ($word_splitters as $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $word) {
                if (in_array(strtoupper($word), $uppercase_exceptions)) {
                    $word = strtoupper($word);
                } elseif (! in_array($word, $lowercase_exceptions)) {
                    $word = ucfirst($word);
                }

                $newwords[] = $word;
            }

            if (in_array(strtolower($delimiter), $lowercase_exceptions)) {
                $delimiter = strtolower($delimiter);
            }

            $string = join($delimiter, $newwords);
        }

        return $string;
    }
}
