<?php
/**
 * Utilities.
 *
 * Useful added functions.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */

namespace lib;

/**
 * Utilities.
 *
 * @link       https://www.philadelphiavotes.com
 *
 * @package    api_web
 * @subpackage api_web/lib
 */
class Utils
{
    /**
     * Singleton.
     *
     * @var object   singleton instance
     */
    private static $instance;

    /**
     * A name-safe formatter.
     *
     * @param      string  $string  The string
     *
     * @return     string  $string a prettier string.
     */
    public function titleCase($string)
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

    public function polygonString2Array($polygon, $label = 'POLYGON')
    {
        $tmp =$output= [];

        $coordString = substr($polygon, strlen($label)+2, strlen($polygon) - strlen($label) - 4);
        $coordPairs = explode(',', $coordString);
        foreach ($coordPairs as $pair) {
            $tmp = explode(' ', $pair);
            array_push($output, $tmp);
        }
        d($output)
        exit;
    }

    /**
     * Gets the (singleton) instance.
     *
     * @return     object  The instance.
     */
    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object();
        }

        return self::$instance;
    }
}
