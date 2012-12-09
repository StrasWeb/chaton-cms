<?php
if(isset($_GET["news"]) || isset($_GET["page"])){
	chdir("../..");
	require_once("classes/dom-enhancer/DOMElement.php");
		require_once("classes/UtfNormal.php");
require_once("classes/Config.php");
$config=new Config();
    $domimpl=new DOMImplementation();
	$doctype=$domimpl->createDocumentType('html');
	$dom=$domimpl->createDocument(null,"html",$doctype);

	$dom->registerNodeClass('DOMElement', 'DOMEnhancer_DOMElement');
	$dom->html=$dom->documentElement;

    if(isset($_GET["news"])){
        		require_once("classes/Article.php");
	$item=new Article($_GET["lang"], $_GET["news"]);
	$curPage="news_".$_GET["news"];
    }else if(isset($_GET["page"])){
        		require_once("classes/Page.php");
       $item=new Page($_GET["lang"], $_GET["page"]);
       $curPage="page_".$_GET["page"];
    }
    if(isset($item->id)){
	$dom->html->addElement("head")->addElement("title", $item->title);
	$dom->html->head->addElement("meta", null, array("charset"=>"UTF-8"));
	if($config->theme) {
	//Fullpath parce que domPDF interprète les URL relatives bizarrement.
	   $dom->html->head->addElement("link", null, array("rel"=>"stylesheet", "href"=>$config->fullpath."../../themes/".$config->theme."/theme.css"));
    }
	$dom->html->addElement("body", null, array("class"=>$curPage." pdf"))->addElement("h1", $item->title);
	$dom->html->body->addElement("div");
	$dom->html->body->div->content=$dom->createDocumentFragment();
    $dom->html->body->div->content->appendXML(UtfNormal::cleanUp(stripslashes($item->content)));
    $dom->html->body->div->appendChild($dom->html->body->div->content);
}


require_once("dompdf/dompdf_config.inc.php");

	$dompdf = new DOMPDF();
$dompdf->load_html($dom->saveHTML());
$dompdf->render();
if(isset($_GET["nopublish"])){
print($dom->saveHTML());
}else if(isset($item->id)){
$dompdf->stream($item->title.".pdf");
}


}
?>
