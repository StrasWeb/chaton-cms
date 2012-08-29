<?php
/**
 * Article class
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
 * Class used to manage external links
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class Link extends Item
{
    static $table="links";
    
    /**
     * Get all links
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name (not used)
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
        return parent::getAll($id_lang, $config->prefix.self::$table);
    }
    
    /**
     * Get a list of links
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name
     * @param string $file    SQL file
     * 
     * @return object
     * */
    static function getList($id_lang=null, $table=null, $file="getLinkList")
    {
        global $config;
        return parent::getList($id_lang, $config->prefix.self::$table, $file);
    }
    
    /**
     * Link constructor
     * 
     * @param string $lang Locale
     * @param string $id   ID
     * 
     * @return void
     * */
    function __construct($lang, $id=0)
    {
        global $config;
        $article=parent::__construct($lang, $id, $config->prefix.self::$table);
        if (is_array($article)) {
            foreach ($article as $key=>$value) {
                $this->$key=$value; 
            }
        } else {
            $this->title='';
            $this->pos=0;
            $this->lang=$lang;
            $this->id='';
            $this->url='';
        }
    }
    
    /**
     * Add a link
     * 
     * @return bool
     * */
    function add()
    {
        global $config;
        $query=file_get_contents("sql/addLink.sql");    
        $query =sprintf($query, $config->prefix.self::$table);    
        $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $param=array($this->title, $this->url, $this->lang);
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            trigger_error($query->errorInfo(), E_USER_WARNING);
            return false;
        }
        
    }
    
    /**
     * Update a link position
     * 
     * @return bool
     * */
    function update()
    {
        global $config;
        $query=file_get_contents("sql/updateLink.sql");    
        $query =sprintf($query, $config->prefix.self::$table);  
        $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $query->bindValue(":pos", $this->pos, PDO::PARAM_INT);
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
        $result=$query->execute();
        if ($result) {
            return true;
        } else {
            trigger_error($query->errorInfo(), E_USER_WARNING);
            return false;
        }
        
    }
    
    /**
     * Delete a link
     * 
     * @param string $table SQL table name (not used)
     * 
     * @return bool
     * */
    function delete($table=null)
    {
        global $config;
        return parent::delete($config->prefix.self::$table);
    }
}
?>
