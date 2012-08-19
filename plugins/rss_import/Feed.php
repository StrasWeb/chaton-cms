<?
if(isset($dom) || isset($this)){
require_once("classes/Plugin.php");
class Feed extends Plugin {
    public $url;
    public $num;
    public $where;
    
    function __construct($dir, $lang=null){
     $this->url=$this->getParam("rss_url");
     $this->num=$this->getParam("rss_num");
     $this->where=$this->getParam("rss_where");  
     parent::__construct($dir); 
    }
}
}
    ?>
