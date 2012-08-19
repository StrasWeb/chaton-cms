<?php
if(isset($dom)){
require_once("Feed.php");
$feed=new Feed($_GET["dir"]);
$dom->html->body->div->div->addElement("h2", _("RSS Feed Import"), array("class"=>"subtitle"));

if(isset($_POST['modified'])) {
    	   $feed->url=$_POST['rss_url'];
	   $feed->num=$_POST['rss_num'];
	   $feed->where=$_POST['where'];	
    if(!@DOMDocument::load($_POST['rss_url'])){
        	   $dom->html->body->div->div->addElement("div", _("Incorrect URL!"), array("class"=>"error"));
    }
	else if($feed->setParam("rss_url", $feed->url) && $feed->setParam("rss_num", $feed->num) && $feed->setParam("rss_where", $feed->where)){	
	   $dom->html->body->div->div->addElement("div", _("Modifications succesfully saved"), array("id"=>"modified"));
	}
}


$dom->html->body->div->div->addElement("form", null, array("action"=>"index.php?tab=plugin&dir=".$feed->dir, "method"=>"post"));
$dom->html->body->div->div->form->addElement("label", _("Feed URL:"), array("for"=>"rss_url", "title"=>_("Adress of the RSS feed to display")));
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("input", null, array("type"=>"url", "id"=>"rss_url", "name"=>"rss_url", "value"=>$feed->url));
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("label", _("Number of articles to display:"), array("for"=>"rss_num"));
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("select", null, array("id"=>"rss_num", "name"=>"rss_num"));
for($i=1;$i<=5;$i++){
    $dom->html->body->div->div->form->select->addElement("option", $i, array("value"=>$i));
	if($i==$feed->num){
	$dom->html->body->div->div->form->select->option->setAttribute("selected", true);
	}
}
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("br");

$dom->html->body->div->div->form->addElement("label", _("Display the feed")." ", array("for"=>"rss_num"));
$dom->html->body->div->div->form->addElement("select", null, array("id"=>"where", "name"=>"where"));
    $dom->html->body->div->div->form->select->addElement("option", _("in a block"), array("value"=>"box"));
	if($feed->where=="box"){
	$dom->html->body->div->div->form->select->option->setAttribute("selected", true);
	}
	 $dom->html->body->div->div->form->select->addElement("option", _("in the menu"), array("value"=>"menu"));
	if($feed->where=="menu"){
	$dom->html->body->div->div->form->select->option->setAttribute("selected", true);
	}
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("br");
$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "name"=>"modified", "value"=>true));
$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit", "value"=>_("Save")));
}
?>
