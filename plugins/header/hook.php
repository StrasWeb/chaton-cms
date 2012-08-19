<?php
if(isset($this)){
global $config, $pages;
require_once("plugins/".$this->dir."/Header.php");
$plugin=new Header($this->dir);
global $dom;
$header=$dom->getElementById("header");
      if(!empty($plugin->content)){
$header->content=$dom->createDocumentFragment();
$header->content->appendXML(stripslashes($plugin->content));
$header->appendChild($header->content);
      }
  }
?>
