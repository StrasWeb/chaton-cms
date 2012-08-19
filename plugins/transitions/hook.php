<?php
if(isset($this) && isset($_GET["trans"])){
	global $dom;
	$this->head=$dom->getElementById("head");
	$this->head->addElement("script", null, array("src"=>"plugins/".$this->dir."/fadeIn.js"));
}
?>
