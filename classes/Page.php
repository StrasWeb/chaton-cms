<?php
require_once("Document.php");
class Page extends Document{
    public $pos=0;
		static $table="pages";
	static function getList($id_lang=null, $table=null, $file="getPageList"){
		global $config;
		return parent::getList($id_lang, $config->prefix.self::$table, $file);
	}
	
	static function getAll($id_lang=null, $table=null, $file="getAll"){
		global $config;
		return parent::getAll($id_lang, $config->prefix.self::$table);
	}
	
	function __construct($lang, $id=0){
		global $config;
		$page=parent::__construct($lang, $id, $config->prefix.self::$table);
		if(is_array($page)){
            foreach($page as $key=>$value){
                $this->$key=$value; 
            }
		}else{
            $this->lang=$lang;
        }
	}
	
		function add($trans=false)
        {
            global $config;
            if($trans){
            $query=file_get_contents("sql/translatePage.sql"); 
            $query =sprintf($query, $config->prefix.self::$table);
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $param=array($this->title, $this->pos, $this->content, $this->lang, $this->id);
		} else {
            $query=file_get_contents("sql/addPage.sql");  
            $query =sprintf($query, $config->prefix.self::$table);     
            $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
            $param=array($this->title, $this->pos, $this->content, $this->lang);
		}
        $result=$query->execute($param);
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
		
	}
	
	function update(){
	   global $config;
		$query=file_get_contents("sql/updatePage.sql"); 
				    $query =sprintf($query, $config->prefix.self::$table, $config->prefix.self::$table);      
        	$query = $config->sql->prepare($query);
        $param=array($this->title, $this->content, $this->lang, $this->id, $this->lang, $this->pos, $this->id);
        $result=$query->execute($param);
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
