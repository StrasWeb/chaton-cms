<?php
if(isset($dom)){
require_once("classes/Item.php");
class Gallery extends Item {
    static $table="chaton_gallery";
    
    static function getAll($id_lang=null, $table=null, $file="getAll"){
		global $config;
		$dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
		$query=file_get_contents("plugins/".$dir."/getGalleryList.sql");  
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
	   $query=file_get_contents("plugins/".$dir."/getGallery.sql"); 
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
	 }else{
	 	$this->id=$id;
		$this->lang="en";
	 }
	 
	 
	 }
	 
	 
	 function delete($table=null){
	 global $config;
	 	return parent::delete($config->prefix.self::$table);
	 }

	function add(){
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
	
	function update(){
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
       
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }

	}
	
}
}
?>
