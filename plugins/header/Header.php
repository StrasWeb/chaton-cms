<?php
if(isset($dom) || isset($this)){
require_once("classes/Plugin.php");
class Header extends Plugin {
    public $content;
    public $title;
    
    function __construct($dir, $lang=null){
        global $config;
        if(!isset($lang)){
			if($config->multilingual){
         $lang=$config->lang;   
		}else {
			$lang="++";
		}
        }
     $this->content=$this->getParam("header_content", $lang);  
     parent::__construct($dir); 
    }
}
}
    ?>
