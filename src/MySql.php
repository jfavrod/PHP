<?php
namespace Epoque;


/**
 * MySql
 * 
 * A wrapper for the php mysqli driver.
 *
 * @author Jason Favrod <jason@epoquecorporation.com>
 */

class MySql
{
    protected static $db_host = DB_HOST;
    protected static $db_name = DB_NAME;
    protected static $db_user = DB_USER;
    protected static $db_pass = DB_PASS;


    /**
     * setup
     * 
     * The MySql object will use defined constants for connection
     * details; this method allows for that behavior to be overridden
     * by providing a specification ($spec) with the connection
     * details.
     * 
     * @param array $spec Contains the connection details for a MySQL
     * database as key value pairs.
     */

    public static function setup($spec) {
        if ($spec['db_host'] || $spec['DB_HOST']) {
            self::$db_host = ($spec['db_host'] ? $spec['db_host'] : $spec['DB_HOST']); 
        }
        if ($spec['db_name'] || $spec['DB_NAME']) {
            self::$db_name = ($spec['db_name'] ? $spec['db_name'] : $spec['DB_NAME']);
        }
        if ($spec['db_user'] || $spec['DB_USER']) {
            self::$db_user = ($spec['db_user'] ? $spec['db_user'] : $spec['DB_USER']); 
        }
        if ($spec['db_pass'] || $spec['DB_PASS']) {
            self::$db_user = ($spec['db_pass'] ? $spec['db_pass'] : $spec['DB_PASS']); 
        }
    }


    /**
     * conn
     * 
     * Return a connection to the configured MySQL database.
     * 
     * @return \mysqli
     */

    private static function conn() {
        return
        new \mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
    }


    /**
     * close
     * 
     * Close a given connection to a MySQL database.
     * 
     * @param type $conn
     */

    private static function close($conn) {
        mysqli_close($conn);
    }


    /**
     * An array containing the headers (attributes) of the given table.
     * 
     * @param string $table The given table's name.
     * @return array Contains the table's headers (attributs).
     */

    public static function headers($table) {
        $conn   = self::conn();
        $db     = self::$db_name;
        $result = [];

        $resultObj = self::select(
            'SELECT `COLUMN_NAME` ' . 
            'FROM `INFORMATION_SCHEMA`.`COLUMNS` ' .
            "WHERE `TABLE_SCHEMA`='$db' " .
            "AND `TABLE_NAME`='$table'"
        );

        foreach ($resultObj as $row) {
            array_push($result, $row['COLUMN_NAME']);
        }
        
        $conn->close();
        return $result;
    }


    /**
     * Execute a select query against the database, resulting rows as
     * an array of associative arrays.
     * 
     * @param string $query A valid MySQL query string.
     * 
     * @return array Contains the rows (each an array of attribute
     * value pairs) of the result of the given query string.
     */
    
    public static function select($query) {
        $conn = self::conn();
        $resultObj = $conn->query($query);
        $result = [];
        
        while ($row = $resultObj->fetch_assoc()) {
            array_push($result, $row);
        }

        $conn->close();
        return $result;
    }
    

    /**
     * Send an insert query to the mysql database.
     * 
     * @param string $query A properly escaped mysql insert statement.
     * @return boolean True if successful, false otherwise.
     */

    public static function insert($query) {
        $conn = self::conn();
        
        if (!$conn->connect_error) {
            $result = $conn->query($query);
            $conn->close();
        }
        else {
            error_log('MySql: mysqli Connection Error (' .$conn->connect_errno. ') '.$conn->connect_error);
        }
        
        return $result;
    }
}

