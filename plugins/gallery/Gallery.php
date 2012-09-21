<?php
/**
 * Gallery plugin
 * Gallery class
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    include_once "classes/Item.php";
    /**
     * Gallery plugin
     * Class used to manage galleries
     *
     * PHP Version 5.3.6
     * 
     * @category Plugin
     * @package  Chaton
     * @author   Pierre Rudloff <rudloff@strasweb.fr>
     * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
     * @link     http://cms.strasweb.fr
     */
    class Gallery extends Item
    {
        static $table="chaton_gallery";
        
        /**
         * Get all galleries
         * 
         * @param string $id_lang Language
         * @param string $table   SQL table
         * @param string $file    SQL file
         * 
         * @return object
         * */
        static function getAll($id_lang=null, $table=null, $file="getAll", $min=null, $max=null)
        {
            global $config;
            $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
            $query=file_get_contents("plugins/".$dir."/getGalleryList.sql");  
            $query =sprintf($query, $config->prefix.self::$table);
            if ($query) {
                $query = $config->sql->prepare($query);
                $query->execute();   
                $result=$query->fetchAll(PDO::FETCH_OBJ);
                return $result;
            } else {
                return print_r($query->errorInfo());
            }
        }
        
        /**
         * Gallery constructor
         * 
         * @param int $id ID
         * 
         * @return void
         * */
        function __construct($id=null)
        {
            global $config;
            if (isset($id)) {
                $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
                $query=file_get_contents("plugins/".$dir."/getGallery.sql"); 
                $query =sprintf($query, $config->prefix.self::$table);
                if ($query) {
                    $query = $config->sql->prepare($query);
                    $query->bindValue(":id", $id, PDO::PARAM_INT);
                    $query->execute();   
                    $result=$query->fetch(PDO::FETCH_ASSOC);
                    if (is_array($result)) {
                        foreach ($result as $key=>$value) {
                            $this->$key=stripslashes($value); 
                        }
                    }
                }
            } else {
                $this->id=$id;
                $this->lang="en";
            }         
        }
         
        /**
         * Delete a gallery
         * 
         * @param string $table SQL table
         * 
         * @return bool
         * */
        function delete($table=null)
        {
            global $config;
            return parent::delete($config->prefix.self::$table);
        }

        /**
         * Add a gallery
         * 
         * @return int ID
         * */
        function add()
        {
            global $config;
            $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
            $query=file_get_contents("plugins/".$dir."/addGallery.sql"); 
            $query =sprintf($query, $config->prefix.self::$table);
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $query->bindValue(":name", $this->name, PDO::PARAM_STR);
            $query->bindValue(":desc", $this->desc, PDO::PARAM_STR);

            $result=$query->execute();
            return $config->sql->lastInsertId();
        }
        
        /**
         * Update a gallery
         * 
         * @return bool
         * */
        function update()
        {
            global $config;
            $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
            $query=file_get_contents("plugins/".$dir."/updateGallery.sql"); 
            $query =sprintf($query, $config->prefix.self::$table);
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $query->bindValue(":name", $this->name, PDO::PARAM_STR);
            $query->bindValue(":desc", $this->desc, PDO::PARAM_STR);
            $query->bindValue(":id", $this->id, PDO::PARAM_INT);
            $query->bindValue(":num", $this->num, PDO::PARAM_INT);

            $result= $query->execute();
           
            if ($result) {
                return true;
            } else {
                print_r($query->errorInfo());
                return false;
            }

        }
        
    }
}
?>
