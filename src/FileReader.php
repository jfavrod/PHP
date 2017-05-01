<?php
namespace Epoque\PHP;


/**
 * FileReader
 *
 * A convenience class for reading files.
 *
 * @author Jason Favrod <jason@epoquecorporation.com>
 */

class FileReader
{
    /** @var resource Holds a file pointer. **/
    private $file = null;


    /**
     * constructor
     *
     * @param string $file The absolute or relative path to the working
     * file.
     * @throws Exception FileNotFoundException.
     */

    public function __construct($file)
    {
        if (!$this->file = fopen($file, 'r')) {
            throw new Exception("FileNotFoundException: File: $file, not found.");
        }
    }


    /**
     * read
     * 
     * Reads a single character from the FileReader.
     *
     * @return char|False A single character or false if there is no
     * characters to read.
     */

    public function read()
    {
        $c = fgetc($this->file);

        if ($c)
            return $c;
        else
            return False;
    }


    /**
     * readln
     *
     * Reads a single line from the give $file.
     *
     * @return string|False A one line string from the file, or False
     * if there are no lines to read.
     */

    public function readln()
    {
        return fgets($this->file);
    }


    public function __destruct()
    {
        fclose($this->file);
    }
}

