<?php
/**
 * Page class
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
require_once "Document.php";
/**
 * Class used to manage pages
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class Page extends Document
{
    public $pos=0;
    static $table="pages";
        
    /**
     * Get a list of pages
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name
     * @param string $file    SQL file
     * 
     * @return object
     * */
    static function getList($id_lang=null, $table=null, $file="getPageList")
    {
        global $config;
        return parent::getList($id_lang, $config->prefix.self::$table, $file);
    }
    
    /**
     * Get all pages
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
     * Page constructor
     * 
     * @param string $lang Locale
     * @param int    $id   ID
     * 
     * @return void
     * */
    function __construct($lang, $id=0)
    {
        global $config;
        $page=parent::__construct($lang, $id, $config->prefix.self::$table);
        if (is_array($page)) {
            foreach ($page as $key=>$value) {
                $this->$key=$value; 
            }
        } else {
            $this->lang=$lang;
        }
    }
    
    /**
     * Add a page
     * 
     * @param bool $trans Is it a translation ?
     * 
     * @return bool
     * */
    function add($trans=false)
    {
        global $config;
        if ($trans) {
            $query=file_get_contents("sql/translatePage.sql"); 
            $query =sprintf($query, $config->prefix.self::$table);
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $param=array(
                $this->title, $this->pos, $this->content,
                $this->lang, $this->id, $this->show
            );
        } else {
            $query=file_get_contents("sql/addPage.sql");  
            $query =sprintf($query, $config->prefix.self::$table);     
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $param=array($this->title, $this->pos, $this->content, $this->lang, $this->show);
        }
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            trigger_error($query->errorInfo(), E_USER_WARNING);
            return false;
        }
        
    }
    
    /**
     * Update a page
     * 
     * @return bool
     * */
    function update()
    {
        global $config;
        $query=file_get_contents("sql/updatePage.sql"); 
        $query =sprintf(
            $query, $config->prefix.self::$table,
            $config->prefix.self::$table
        );      
        $query = $config->sql->prepare($query);
        $param=array(
            $this->title, $this->content, $this->lang, $this->show,
            $this->id, $this->lang, $this->pos, $this->id
        );
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            trigger_error($query->errorInfo(), E_USER_WARNING);
            return false;
        }
        
    }
    
    /**
     * Delete a page
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
