<?php

/**
 * ODB
 *
 * A wrapper for the PHP oci_* (Oracle database driver) functions.
 * 
 * When used in static context, must have DB_USER, DB_PASS, DB_CONN,
 * and DB_CHARSET as defined constants; otherwise call the constructor
 * with these elements in an associative array.
 *
 * @author Jason Favrod <jason@epoquecorporation.com>
 */

namespace Epoque\PHP;


class ODB
{
    private static $conn       = null;
    private static $db_user    = DB_USER;
    private static $db_pass    = DB_PASS;
    private static $db_conn    = DB_CONN;
    private static $db_charset = DB_CHARSET;

    
    /**
     * constructor
     * 
     * Initializes the ODB object's connection details (db_user, db_pass,
     * db_conn, and optionally db_charset) from the $details parameter.
     * 
     * @param Array|null $details
     * Associative Array containing the database details.
     */
    
    public function __construct($details = null)
    {
        if ($details) {
            self::$db_user    = $details['db_user'];
            self::$db_pass    = $details['db_pass'];
            self::$db_conn    = $details['db_conn'];
            self::$db_charset = $details['db_charset'];
            
            if (!self::$db_charset) {
                self::$db_charset = 'AL32UTF8';
            }
        }
        else {
            throw new Exception('ODB failed to construct on details: '
                    . 'db_user:' . $details['db_user'] . ', '
                    . 'db_pass:' . $details['db_pass'] . ', '
                    . 'db_conn:' . $details['db_conn'] . ', '
                    . 'db_charset:' . $details['db_charset']);
        }
    }


    /**
     * connect
     * 
     * Provides a connection to the database.
     * 
     * @return Resource|False A connection identifier or FALSE on error.
     */
    
    public static function connect()
    {
        if (self::$conn === null) {
            self::$conn = oci_connect(self::$db_user, self::$db_pass, self::$db_conn, self::$db_charset);
            self::select("ALTER SESSION SET NLS_DATE_FORMAT = 'MM/DD/YYYY'");
        }
        
        return self::$conn;
    }

    
    /**
     * select
     * 
     * Performs a select query on the database.
     * 
     * @param String $query A given select query.
     * 
     * @return Array Containing the tuples returned from the request.
     */
    
    public static function select($query)
    {
        $statement = null;
        $result    = [];


        if (self::connect()) {
            $statement = oci_parse(self::connect(), $query);
            oci_execute($statement);

            while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
                array_push($result, $row);
            }
            
            self::close();
        }

        return $result;
    }

    
    /**
     * insert
     * 
     * Performs a given insert query on the database.
     * 
     * @param string $query A given insert query.
     * 
     * @return string 'true' on successful insert 'false' otherwise.
     */
    
    public static function insert($query)
    {
        $statement = null;
        $result = 'false';
        
        if(self::connect()) {
            $statement = oci_parse(self::connect(), $query);
            
            if (oci_execute($statement)) {
                $result = 'true';
            }
            
            self::close();
        }
        
        return $result;
    }
    

    /**
     * close
     * 
     * Closes the database connection.
     * 
     * @return void
     */
    
    public static function close()
    {
        if (self::$conn) {
            oci_close(self::$conn);
        }
    }
}


