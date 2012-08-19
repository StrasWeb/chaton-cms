<?php
	chdir("../..");
	require_once("classes/Config.php");
	$config=new Config();
    $domimpl=new DOMImplementation();
	$doctype=$domimpl->createDocumentType('html');
	$dom=$domimpl->createDocument("http://www.w3.org/1999/xhtml","html",$doctype);
	require_once("classes/DOMElement.php");
	require_once("classes/Plugin.php");
	$dom->registerNodeClass('DOMElement', 'NewDOMElement');
	$dom->html=$dom->documentElement;
	$dom->html->addElement("head")->addElement("title", $config->title." - "._("Terms of Sale"));
	$dom->html->addElement("body")->addElement("div");
	$dom->html->body->div->content=$dom->createDocumentFragment();
	if($dom->html->body->div->content->appendXML(stripslashes(Plugin::getParam("shop_tos")))){
		$dom->html->body->div->appendChild($dom->html->body->div->content);
	}
	print($dom->saveHTML());
?>
