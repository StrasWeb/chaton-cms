<?php
if(isset($dom)){
require_once("classes/Item.php");
class GalleryImage extends Item {
    static $table="chaton_gallery_images";
    
    static function getAll($id_lang=null, $table=null, $file="getAll"){
		global $config;
		return parent::getAll($id_lang, $config->prefix.self::$table);
	}
	
	static function getImages($id_gallery){
		global $config;
		$dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
		$query=file_get_contents("plugins/".$dir."/getList.sql");  
		$query =sprintf($query, $config->prefix.self::$table);
        if($query){
        	$query = $config->sql->prepare($query);
        $query->bindValue(":id_gallery", $id_gallery, PDO::PARAM_INT);
        $query->execute();   
        $result=$query->fetchAll(PDO::FETCH_OBJ);
                                return $result;
                        }else{
                                return print_r($query->errorInfo());
                        }
	}
	
	function __construct($id_gallery, $id){
	   global $config;
	   if(isset($id)){
	   $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
	   $query=file_get_contents("plugins/".$dir."/getImage.sql"); 
     $query =sprintf($query, $config->prefix.self::$table);
        if($query){
        	$query = $config->sql->prepare($query);
        $query->bindValue(":id_gallery", $id_gallery, PDO::PARAM_INT);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();   
        $result=$query->fetch(PDO::FETCH_ASSOC);
		if(is_array($result)){
		foreach($result as $key=>$value){
		 $this->$key=stripslashes($value); 
		}
        }
	}
	}
	}

	
	static function add($id_gallery, $type){
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
	
	 function delete($table=null){
	 global $config;
	 unlink(getcwd()."/plugins/".$_GET["dir"]."/images/".$this->id.".".GalleryImage::getExt($this->type));
	 	return parent::delete($config->prefix.self::$table);
	 }
	
	static function getExt($type){
		/*switch($type){
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
	
	function update(){
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
       
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }

	}
	
}
}
?>
