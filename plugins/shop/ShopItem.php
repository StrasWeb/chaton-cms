<?php
if(isset($dom)){
require_once("classes/Item.php");
class ShopItem extends Item {
    static $table="chaton_shop";
    
    static function getAll($id_lang=null, $table=null, $file="getAll"){
		global $config;
		$dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
		$query=file_get_contents("plugins/".$dir."/".$file.".sql");  
		$query =sprintf($query, $config->prefix.self::$table);
        if($query){
        	$query = $config->sql->prepare($query);
        $query->execute();   
        $result=$query->fetchAll(PDO::FETCH_OBJ);
                                return $result;
                        }else{
                                return print_r($query->errorInfo());
                        }
	}
	
	function __construct($id=null){
	   global $config;
	   if(isset($id)){
	   $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
	   $query=file_get_contents("plugins/".$dir."/getItem.sql"); 
     $query =sprintf($query, $config->prefix.self::$table);
        if($query){
        	$query = $config->sql->prepare($query);
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

	
	function add(){
		global $config;
		$dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
		$query=file_get_contents("plugins/".$dir."/addItem.sql"); 
     $query =sprintf($query, $config->prefix.self::$table);
     $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
	$query->bindValue(":name", $this->name, PDO::PARAM_STR);
	$query->bindValue(":price", $this->price, PDO::PARAM_INT);
	$query->bindValue(":desc", $this->desc, PDO::PARAM_STR);
	$query->bindValue(":ref", $this->ref, PDO::PARAM_STR);
       $result=$query->execute();
        if($result){
       return $config->sql->lastInsertId();
       }else{
        print_r($query->errorInfo());
       }
	}
	
	 function delete($table=null){
	 global $config;
	 	//unlink(getcwd()."/plugins/".$_GET["dir"]."/images/".$this->id.".".ShopItem::getExt($this->type));
	 	return parent::delete($config->prefix.self::$table);
	 }
	
	static function getExt($type){
		switch($type){
				case "image/jpeg":
					return "jpg";
					break;
				case "image/png":
					return "png";
					break;
				case "video/mp4":
					return "mp4";
					break;
			}
	}
	
	function update(){
		global $config;
		$dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
		$query=file_get_contents("plugins/".$dir."/updateItem.sql"); 
     $query =sprintf($query, $config->prefix.self::$table);
     $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
	$query->bindValue(":name", $this->name, PDO::PARAM_STR);
	$query->bindValue(":ref", $this->ref, PDO::PARAM_STR);
	$query->bindValue(":desc", $this->desc, PDO::PARAM_STR);
	$query->bindValue(":type", $this->type, PDO::PARAM_STR);
	$query->bindValue(":type2", $this->type2, PDO::PARAM_STR);
	$query->bindValue(":price", $this->price, PDO::PARAM_STR);
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
