<?php
namespace Epoque;


/**
 * Common
 *
 * A class containing methods for common tasks.
 *
 * @author Jason Favrod <jason@epoquecorporation.com>
 */

class Common
{
    /**
     * dirscan
     *
     * List files and directories inside the specified path.
     * An improvement to PHP's scandir.
     *
     * @param string $dir The path to the directory.
     * @param
     */

    public static function dirscan($dir, $opts=[]) {
        $dirscan = scandir($dir);
        $ignored = [];
        
        // Ignore hidden unless there's a 'hidden' argument.
        if (empty($opts['hidden'])) {
            $ignored = ['.', '..'];
        }
        
        // Ignore the items in the ignored array.
        if (!empty($opts['ignored'])) {
            $ignored = array_merge($ignored, $opts['ignored']);
        }
        
        for ($i = 0; $i < count($dirscan); $i++) {
            if (in_array($dirscan[$i], $ignored)) {
                unset($dirscan[$i]);
            }
        }
        
        return $dirscan;
    }
}
