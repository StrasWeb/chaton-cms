<?php
if(isset($dom)){
require_once("Header.php");
 $lang=isset($_GET['lang'])?$_GET['lang']:null;
$plugin=new Header($_GET["dir"], $lang);
$dom->html->body->div->div->addElement("script", "", array("src"=>"tiny_mce/tiny_mce.js", "type"=>"text/javascript"));
$dom->html->body->div->div->addElement("script", "", array("src"=>"tiny_mce.js", "type"=>"text/javascript"));

$dom->html->body->div->div->addElement("h2", _("Custom header"), array("class"=>"subtitle"));
if(isset($_GET["lang"])){
    $dom->html->body->div->div->h2->addElement("span", " (".$_GET["lang"].")");
}

if(isset($_POST['modified'])) {
	$plugin->content=$_POST['content'];	
	$lang=isset($_POST['lang'])?$_POST['lang']:"++";
	if($plugin->setParam("header_content", $plugin->content, $lang)){	
		$dom->html->body->div->div->addElement("div", _("Modifications succesfully saved"), array("class"=>"modified"));
	}
}
$dom->html->body->div->div->addElement("form",null, array("action"=>"index.php?tab=plugin&dir=".$plugin->dir, "method"=>"post"));
		$dom->html->body->div->div->form->addElement("label", _("Content:"), array("for"=>"content"));
		$dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("textarea", null, array("rows"=>"25", "cols"=>"25", "id"=>"content", "name"=>"content", "value"=>$plugin->title));
		if(!empty($plugin->content)){
		$dom->html->body->div->div->form->textarea->content=$dom->createDocumentFragment();
$dom->html->body->div->div->form->textarea->content->appendXML(html_entity_decode(stripslashes($plugin->content)));
$dom->html->body->div->div->form->textarea->appendChild($dom->html->body->div->div->form->textarea->content);
		}
	
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "name"=>"modified", "value"=>true));
		$dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("br");
		if(isset($_GET["lang"])){
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "name"=>"lang", "value"=>$_GET["lang"]));
		}

$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit", "value"=>_("Save")));
}
?>
