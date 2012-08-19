<?php
if(isset($this) && !$this->installed){
        $query=file_get_contents("plugins/".$this->dir."/install.sql");   
        $query =sprintf($query, $config->prefix."chaton_guestbook");     
        $query = $config->sql->prepare($query);
       $result=$query->execute();
       if($result){
                                return true;
                        }else{
                                print_r($query->errorInfo());
                                return false;
                        }
		
}
?>