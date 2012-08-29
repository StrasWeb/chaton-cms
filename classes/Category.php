<?php
/**
 * Category class
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
require_once "Item.php";
/**
 * Class used to manage categories
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class Category extends Item
{
    static $table="categories";
    
    /**
     * Category constructor
     * 
     * @return void
     * */
    function __construct ()
    {
        
    }
    
    /**
     * Add a category
     * 
     * @return bool
     * */
    function add()
    {
        global $config;
        $query=file_get_contents("sql/addCat.sql");    
        $query =sprintf($query, $config->prefix.self::$table);    
        $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $param=array($this->title);
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            trigger_error($query->errorInfo(), E_USER_WARNING);
            return false;
        }
        
    }
    
     /**
     * Get all categories
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
        return parent::getAll(null, $config->prefix.self::$table);
    }
    
    /**
     * Get a list of categories
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name
     * @param string $file    SQL file
     * 
     * @return object
     * */
    static function getList($id_lang=null, $table=null, $file="getCatList")
    {
        global $config;
        return parent::getList($id_lang, $config->prefix.self::$table, $file);
    }
}
?>
