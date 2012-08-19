<?php
if(isset($dom)){
require_once("classes/Item.php");
class GBComment extends Item {
    static $table="chaton_guestbook";
    
    static function getAll($id_lang=null, $table=null, $file="getAll"){
    global $config;
		return parent::getAll($id_lang, $config->prefix.self::$table);
	}
	
	function __construct($id=null){
	   global $config;
	   $dir=isset($_GET["dir"])?$_GET["dir"]:$_GET["plugin"];
	   $query=file_get_contents("plugins/".$dir."/get.sql"); 
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
		}else{
		 $this->comment=isset($_POST["comment"])?$_POST["comment"]:""; 
	 $this->name=isset($_POST["name"])?$_POST["name"]:""; 
	 $this->location=isset($_POST["location"])?$_POST["location"]:null; 
	 $this->age=isset($_POST["age"])?$_POST["age"]:null; 
		}
        }
	}
	
	function add($dir){
		global $config;
		$query=file_get_contents("plugins/".$dir."/add.sql");     
		$query =sprintf($query, $config->prefix.self::$table);   
        	$query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $param=array($this->comment, $this->name, $this->age, $this->location);
       $result=$query->execute($param);
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
		
	}
	
	function delete($table=null){
		global $config;
		$query=file_get_contents("plugins/".$_GET["dir"]."/delete.sql");   
		$query =sprintf($query, $config->prefix.self::$table);  
        	$query = $config->sql->prepare($query);
        	 $query->bindValue(":id", $this->id, PDO::PARAM_INT);
       $result=$query->execute();
                        if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
	}
}
}
?>
