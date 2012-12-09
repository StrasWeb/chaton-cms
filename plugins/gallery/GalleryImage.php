<?php
/**
 * Gallery plugin
 * GalleryImage class
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
     * Class used to manage images in galleries
     *
     * PHP Version 5.3.6
     * 
     * @category Plugin
     * @package  Chaton
     * @author   Pierre Rudloff <rudloff@strasweb.fr>
     * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
     * @link     http://cms.strasweb.fr
     */
    class GalleryImage extends Item
    {
        static $table="gallery_images";
        
        /**
         * Get all images
         * 
         * @param string $id_lang Language
         * @param string $table   SQL table
         * @param string $file    SQL file
         * @param int    $min     First image to get
         * @param int    $max     Last image to get
         * 
         * @return array
         * */
        
        static function getAll(
            $id_lang=null, $table=null, $file="getAll",
            $min=null, $max=null
        ) {
            global $config;
            return parent::getAll($id_lang, $config->prefix.self::$table);
        }
        
        /**
         * Get images from a gallery
         * 
         * @param int $id_gallery Gallery ID
         * 
         * @return object
         * 
         * */
        static function getImages($id_gallery)
        {
            global $config;
            $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
            $query=file_get_contents("plugins/".$dir."/getList.sql");  
            $query =sprintf($query, $config->prefix.self::$table);
            if ($query) {
                $query = $config->sql->prepare($query);
                $query->bindValue(":id_gallery", $id_gallery, PDO::PARAM_INT);
                $query->execute();   
                $result=$query->fetchAll(PDO::FETCH_OBJ);
                return $result;
            } else {
                return print_r($query->errorInfo());
            }
        }
        
        /**
         * GalleryImage constructor
         * 
         * @param int $id_gallery Gallery ID
         * @param int $id         Image ID
         * 
         * @return void
         * */
        function __construct($id_gallery, $id)
        {
            global $config;
            if (isset($id)) {
                $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
                $query=file_get_contents("plugins/".$dir."/getImage.sql"); 
                $query =sprintf($query, $config->prefix.self::$table);
                if ($query) {
                    $query = $config->sql->prepare($query);
                    $query->bindValue(":id_gallery", $id_gallery, PDO::PARAM_INT);
                    $query->bindValue(":id", $id, PDO::PARAM_INT);
                    $query->execute();   
                    $result=$query->fetch(PDO::FETCH_ASSOC);
                    if (is_array($result)) {
                        foreach ($result as $key=>$value) {
                            $this->$key=stripslashes($value); 
                        }
                    }
                }
            }
        }

        
        /**
         * Add an image
         * 
         * @param int    $id_gallery Gallery to add the image to
         * @param string $type       MIME type
         * 
         * @return int Image ID
         * */
        static function add($id_gallery, $type)
        {
            global $config;
            $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
            $query=file_get_contents("plugins/".$dir."/addImage.sql"); 
            $query =sprintf($query, $config->prefix.self::$table);
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $query->bindValue(":id_gallery", $id_gallery, PDO::PARAM_INT);
            $query->bindValue(":type", $type, PDO::PARAM_INT);

            $result=$query->execute();
           
            return $config->sql->lastInsertId();
        }
        
        /**
         * Delete an image
         * 
         * @param string $table SQL table
         * 
         * @return bool
         * */
        function delete($table=null)
        {
            global $config;
            unlink(
                getcwd()."/plugins/".$_GET["dir"]."/images/".$this->id.
                ".".GalleryImage::getExt($this->type)
            );
            return parent::delete($config->prefix.self::$table);
        }
        
        /**
         * Get extension from MIME type
         * 
         * @param string $type MIME type
         * 
         * @return string
         * */
        static function getExt($type)
        {
            /*switch($type) {
                    case "image/jpeg":
                        return "jpg";
                        break;
                    case "image/png":
                        return "png";
                        break;
                    case "video/mp4":
                        return "mp4";
                        break;
                }*/
            return basename($type);
        }
        
        /**
         * Update an image
         * 
         * @return bool
         * */
        function update()
        {
            global $config;
            $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
            $query=file_get_contents("plugins/".$dir."/updateGalleryImage.sql"); 
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
