<?php
if(isset($dom) || isset($this)){
require_once("classes/Plugin.php");
class Footer extends Plugin {
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
     $this->default=$this->getParam("footer_default", $lang);
     $this->content=$this->getParam("footer_content", $lang);  
     parent::__construct($dir); 
    }
}
}
    ?>
