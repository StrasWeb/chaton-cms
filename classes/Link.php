<?php
require_once("Item.php");
class Link extends Item{
	static $table="links";
	
	static function getAll($id_lang=null, $table=null, $file="getAll"){
		global $config;
		return parent::getAll($id_lang, $config->prefix.self::$table);
	}
	static function getList($id_lang=null, $table=null, $file="getLinkList"){
		global $config;
		return parent::getList($id_lang, $config->prefix.self::$table, $file);
	}
	
	function __construct($lang, $id=0){
		global $config;
		$article=parent::__construct($lang, $id, $config->prefix.self::$table);
		if(is_array($article)){
		foreach($article as $key=>$value){
		 $this->$key=$value; 
		}
		}else{
		$this->title='';
		$this->pos=0;
		$this->lang=$lang;
		$this->id='';
		$this->url='';
	}
	}
	
	function add($trans=false){
		global $config;
		$query=file_get_contents("sql/addLink.sql");    
		$query =sprintf($query, $config->prefix.self::$table);    
        	$query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $param=array($this->title, $this->url, $this->lang);
       $result=$query->execute($param);
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
		
	}
	
	function update(){
				global $config;
		$query=file_get_contents("sql/updateLink.sql");    
		$query =sprintf($query, $config->prefix.self::$table);  
        	$query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
        $query->bindValue(":pos", $this->pos, PDO::PARAM_INT);
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
       $result=$query->execute();
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
		
	}
	
	function delete($table=null){
		global $config;
		return parent::delete($config->prefix.self::$table);
	}
}
?>
