<?php
abstract class Item{
	public $id;
	public $lang;
	public $title;
	static $table;
	
	static function getNum($id_lang){
		global $config;
		global $table;
		$query=file_get_contents("sql/getAll.sql");
        if($query){
        	$query = $config->sql->prepare($query);
        $query->bindValue(":table", $table, PDO::PARAM_STR);
        $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);

        $query->execute();   
        $result=$query->fetchAll(PDO::FETCH_OBJ);
                                $result=$query->rowCount();
                                return $result;
                        }else{
                                return print_r($query->errorInfo());
                        }

	}

	
	static function getList($id_lang=null, $table=null, $file="getList"){
		global $config;
		if($config->multilingual && isset($id_lang)){
		  		  		$query=file_get_contents("sql/".$file."ByLang.sql");  
		}else{
		  		  		$query=file_get_contents("sql/".$file.".sql");  
		}
		$query =sprintf($query, $table);
        if($query){
        	$query = $config->sql->prepare($query);
        	if($config->multilingual){
        $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
        	}
        $query->execute();   
        $result=$query->fetchAll(PDO::FETCH_OBJ);
                                return $result;
                        }else{
                                return print_r($query->errorInfo());
                        }
	}
	
	static function getAll($id_lang=null, $table=null, $file="getAll"){
		global $config;
		if($config->multilingual && isset($id_lang)){
		  		  		$query=file_get_contents("sql/".$file."ByLang.sql");  
		}else{
		  		  		$query=file_get_contents("sql/".$file.".sql");  
		}
				$query =sprintf($query, $table);
        if($query){
        	$query = $config->sql->prepare($query);
        	if($config->multilingual && isset($id_lang)){
        $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
        	}
        $query->execute();   
        $result=$query->fetchAll(PDO::FETCH_OBJ);
                                return $result;
                        }else{
                                return print_r($query->errorInfo());
                        }
	}
	
		function __construct($id_lang, $id, $table){
            global $config;
            if ($config->multilingual) {
                $query=file_get_contents("sql/get.sql");
            } else {
                $query=file_get_contents("sql/getAnyLang.sql");
            }
            $query =sprintf($query, $table);
            if ($query) {
                $query = $config->sql->prepare($query);
                if ($config->multilingual) {
                    $query->bindValue(":lang", $id_lang, PDO::PARAM_INT);
                }
                $query->bindValue(":id", $id, PDO::PARAM_INT);
                $query->execute();   
                $result=$query->fetch(PDO::FETCH_ASSOC);
                return $result;
            }else{
                return print_r($query->errorInfo());
            }
           
	}
	
		function delete($table=null){
            global $config;
            $query=file_get_contents("sql/delete.sql");   
            $query =sprintf($query, $table);
            $query = $config->sql->prepare($query);
            $query->bindValue(":id", $this->id, PDO::PARAM_INT);
            $query->bindValue(":lang", $this->lang, PDO::PARAM_STR);
            $result=$query->execute();
            if($result){
                    return true;
            }else{
                    print_r($query->errorInfo());
            }
        }
	
}
?>
