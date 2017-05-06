<?php
namespace Epoque\PHP;


/**
 * A class used to store and retrieve instances of objects.
 *
 * @author Jason Favrod <jason@epoquecorporation.com>
 */

class Registry
{
    private static $_store    = [];
    private static  $htmlClass = 'RegistryTable';


    public static function set($object, $name=null)
    {
        if (is_null($name)) {
            $name = get_class($object);
        }

        if (!isset(self::$_store[$name])) {
            self::$_store[$name] = $object;
        }
    }


    public static function get($name)
    {
        if (array_key_exists($name, self::$_store)) {
            return self::$_store[$name];
        }
    }


    public function __toString()
    {
        $_string = '';
        foreach (self::$_store as $name => $object) {
            $_string .= $name . ': ' . get_class($object) . "\n";
        }

        return $_string;
    }


    public static function toHtml()
    {
        $_html = '<table class="'.self::$htmlClass.'">'."\n";
        $_html .= "\t<tr><th>Name</th><th>Type</th></tr>\n";

        foreach (self::$_store as $name => $object) {
            $_html .= "\t<tr><td>$name</td><td>" . get_class($object) . "</td></tr>\n";
        }

        $_html .= "</table>\n";
        return $_html;
    }
}



