<?php
/**
 * Document class
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
 * Class used to manage news articles
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
class Article extends Document
{
    public $title;
    public $content;
    public $id;
    
    static $table="news";
    
    /**
     * Article class constructor
     * 
     * @param string $lang Locale
     * @param int    $id   ID
     * 
     * @return void
     * */
    function __construct($lang, $id=0)
    {
        global $config;
        $article=parent::__construct($lang, $id, $config->prefix.self::$table);
        if (is_array($article)) {
            foreach ($article as $key=>$value) {
                $this->$key=stripslashes($value); 
            }
        } else {
            $this->date=date("Y-m-d");
            $this->lang=$lang;
        }
    }
    
    /**
     * Update an existing article
     * 
     * @return bool
     * */
    function update()
    {
        global $config;
        $query=file_get_contents("sql/updateArticle.sql"); 
        $query =sprintf($query, $config->prefix.self::$table);      
        $query = $config->sql->prepare($query);
        $param=array(
            $this->title, $this->content, $this->lang, $this->date,
            $this->id, $this->lang
        );
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }
        
    }
    
    /**
     * Add an article
     * 
     * @param bool $trans Is it a translation of an existing article ?
     * 
     * @return bool
     * */
    function add ($trans=false)
    {
        global $config;
        if ($trans) {
            $query=file_get_contents("sql/translateArticle.sql"); 
            $query =sprintf($query, $config->prefix.self::$table);
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $param=array(
                $this->title, $this->date, $this->content,
                $this->lang, $this->id
            );

        } else {
            $query=file_get_contents("sql/addArticle.sql"); 
            $query =sprintf($query, $config->prefix.self::$table); 
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $param=array($this->title, $this->date, $this->content, $this->lang);

        }     
            
        $result=$query->execute($param);
        if ($result) {
            return true;
        } else {
            print_r($query->errorInfo());
        }

    }
    
    /**
     * Get article list
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name (not used)
     * @param string $file    SQL file
     * 
     * @return object
     * */
    static function getList($id_lang=null, $table=null, $file="getArticleList")
    {
        global $config;
        return parent::getList($id_lang, $config->prefix.self::$table, $file);
    }
    
    /**
     * Get all articles
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name (not used)
     * @param string $file    SQL file
     * 
     * @return object
     * */
    static function getAll($id_lang=null, $table=null, $file="getArticleAll")
    {
        global $config;
        return parent::getAll($id_lang, $config->prefix.self::$table, $file);
    }
    
    /**
     * Delete an article
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
    
    /**
     * Get number of articles
     * 
     * @param string $id_lang Locale used
     * @param string $table   SQL table name (not used)
     * 
     * @return int
     * */
    static function getNum($id_lang=null, $table=null)
    {
        global $config;
        return parent::getNum($id_lang, $config->prefix.self::$table);
    }
    
    
    
}
?>
