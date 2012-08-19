<?php
if(isset($this)){
global $config, $pages;
require_once("plugins/".$this->dir."/Footer.php");
$plugin=new Footer($this->dir);
global $dom;
$footer=$dom->getElementById("footer");
if(!$plugin->default){
	$footer->removeChild($footer->childNodes->item(0));
}
      if(!empty($plugin->content)){
$footer->content=$dom->createDocumentFragment();
$footer->content->appendXML(stripslashes($plugin->content));
$footer->appendChild($footer->content);
      }
  }
?>
