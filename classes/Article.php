<?php
require_once("Document.php");
class Article extends Document{
	public $title;
	public $content;
	public $id;
	
	static $table="news";
	function __construct($lang, $id=0){
		global $config;
		$article=parent::__construct($lang, $id, $config->prefix.self::$table);
		if(is_array($article)){
		foreach($article as $key=>$value){
		 $this->$key=stripslashes($value); 
		}
		}else{
		$this->date=date("Y-m-d");
		$this->lang=$lang;
	}
	}
	function update(){
		global $config;
		$query=file_get_contents("sql/updateArticle.sql"); 
				    $query =sprintf($query, $config->prefix.self::$table);      
        	$query = $config->sql->prepare($query);
        $param=array($this->title, $this->content, $this->lang, $this->date, $this->id, $this->lang);
       $result=$query->execute($param);
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
		
	}
	function add($trans=false){
		global $config;
		if($trans){
		  $query=file_get_contents("sql/translateArticle.sql"); 
		  $query =sprintf($query, $config->prefix.self::$table);
		  $query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
		          $param=array($this->title, $this->date, $this->content, $this->lang, $this->id);

		}else{
		$query=file_get_contents("sql/addArticle.sql"); 
		$query =sprintf($query, $config->prefix.self::$table); 
		$query = $config->sql->prepare($query, array(PDO::PARAM_NULL));
		        $param=array($this->title, $this->date, $this->content, $this->lang);

		}     
        	
       $result=$query->execute($param);
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                        }
		
	}
	static function getList($id_lang=null, $table=null, $file="getArticleList"){
		global $config;
		return parent::getList($id_lang, $config->prefix.self::$table, $file);
	}
	
	static function getAll($id_lang=null, $table=null, $file="getArticleAll"){
		global $config;
		return parent::getAll($id_lang, $config->prefix.self::$table, $file);
	}
	function delete($table=null){
		global $config;
		return parent::delete($config->prefix.self::$table);
	}
	
	
	
}
?>
