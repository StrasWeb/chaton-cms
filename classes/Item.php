<?php
/**
 * Item class
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
 
 /**
 * Abstract class used to manage items (pages, articles, etc)
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
abstract class Item
{
    public $id;
    public $lang;
    public $title;
    static $table;
    
    /**
     * Get number of items of a type
     * 
     * @param string $id_lang Locale
     * @param string $table   SQL table name 
     * 
     * @return int
     * */
    static function getNum($id_lang=null, $table=null)
    {
        global $config;
        if ($config->multilingual && isset($id_lang)) {
            $query=file_get_contents("sql/getNumByLang.sql");  
        } else {
            $query=file_get_contents("sql/getNum.sql");  
        }
        $query = sprintf($query, $table);
        if ($query) {
            $query = $config->sql->prepare($query);
            if ($config->multilingual) {
                $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
            }

            $query->execute();   
            if ($result=$query->fetch(PDO::FETCH_BOTH)) {
                return intval($result[0]);
            } else {
                print_r($query->errorInfo());
                return false;
            }
        } else {
            print_r($query->errorInfo());
            return false;
        }

    }

    /**
     * Get a list of items
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name
     * @param string $file    SQL file
     * 
     * @return object
     * */
    static function getList($id_lang=null, $table=null, $file="getList")
    {
        global $config;
        if ($config->multilingual && isset($id_lang)) {
            $query=file_get_contents("sql/".$file."ByLang.sql");  
        } else {
            $query=file_get_contents("sql/".$file.".sql");  
        }
        $query =sprintf($query, $table);
        if ($query) {
            $query = $config->sql->prepare($query);
            if ($config->multilingual) {
                $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
            }
            $query->execute();   
            $result=$query->fetchAll(PDO::FETCH_OBJ);
            return $result;
        } else {
            return print_r($query->errorInfo());
        }
    }
    
    /**
     * Get all items from a type
     * 
     * @param string $id_lang Locale
     * @param int    $table   SQL table name
     * @param string $file    SQL file
     * @param int    $min     First item to get
     * @param int    $max     Last item to get
     * 
     * @return object
     * */
    static function getAll(
        $id_lang=null, $table=null, $file="getAll", $min=null, $max=null
    ) {
        global $config;
        $min = $config->min;
        $max = $config->max;
        if ($config->multilingual && isset($id_lang)) {
            $query=file_get_contents("sql/".$file."ByLang.sql");  
        } else {
            $query=file_get_contents("sql/".$file.".sql");  
        }
                $query =sprintf($query, $table);
        if ($query) {
            $query = $config->sql->prepare($query);
            if ($config->multilingual && isset($id_lang)) {
                $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
            }
            $query->bindValue(":min", $min, PDO::PARAM_INT);
            $query->bindValue(":max", $max, PDO::PARAM_INT);
            $query->execute();   
            if ($result=$query->fetchAll(PDO::FETCH_OBJ)) {
                return $result;
            } else {
                $error=$query->errorInfo();
                if ($error[2]) {
                    trigger_error($error[2], E_USER_WARNING);
                }
                return false;
            }
        } else {
            print_r($query->errorInfo());
            return false;
        }
    }
    
    
    /**
     * Item class constructor
     * 
     * @param string $id_lang Locale
     * @param int    $id      ID
     * @param string $table   SQL table name
     * 
     * @return array
     * */
    function __construct($id_lang, $id, $table)
    {
        global $config;
        if ($config->multilingual) {
            $query=file_get_contents("sql/get.sql");
        } else {
            $query=file_get_contents("sql/getAnyLang.sql");
        }
        $query =sprintf($query, $table);
        if ($query) {
            $query = $config->sql->prepare($query);
            if ($config->multilingual) {
                $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
            }
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->execute();   
            $result=$query->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return print_r($query->errorInfo());
        }
           
    }
    
    /**
     * Delete an item
     * 
     * @param string $table SQL table name
     * 
     * @return bool
     * */
    function delete($table=null)
    {
        global $config;
        $query=file_get_contents("sql/delete.sql");   
        $query =sprintf($query, $table);
        $query = $config->sql->prepare($query);
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
        $query->bindValue(":lang", $this->lang, PDO::PARAM_STR);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            trigger_error($query->errorInfo(), E_USER_WARNING);
            return false;
        }
    }
    
}
?>
