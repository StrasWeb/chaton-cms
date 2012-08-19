<?php
	chdir("../..");
	require_once("classes/Config.php");
	$config=new Config();
    require_once("classes/XMLDocument.php");
	$dom=XMLDocument::create();
	require_once("classes/Plugin.php");
	require("inc/localization.php");
if(isset($_GET["accept"])){
	require_once("ShopItem.php");
	$dom->html->addElement("head")->addElement("title", $config->title." - "._("Order Form"));
	$dom->html->head->addElement("link", null, array("rel"=>"stylesheet", "href"=>$config->fullpath."order.css"));
	if($config->theme) {
	$dom->html->head->addElement("link", null, array("rel"=>"stylesheet", "href"=>$config->fullpath."../../themes/".$config->theme."/theme.css"));
	}
	if(!isset($_GET["nojs"])){
		$dom->html->head->addElement("script", null, array("src"=>"print.js"));	
	}
	$dom->html->head->addElement("meta", null, array("charset"=>"utf-8"));
	$dom->html->body->setAttribute("class", "order");
	$dom->html->body->addElement("noscript", _("Click on File > Print in your browser to print this order form."));
	if(isset($_COOKIE["cart"])){
    		$cart=unserialize(stripslashes($_COOKIE["cart"]));
    		}
    		$dom->html->body->addElement("h1", $config->title." - "._("Order Form"))->addElement("br");
    		$dom->html->body->h1->addElement("img", null, array("src"=>$config->logo, "alt"=>""));
   $dom->html->body->addElement("div");
   $dom->html->body->div->content=$dom->createDocumentFragment();
    $dom->html->body->div->content->appendXML(stripslashes(Plugin::getParam("shop_address")));
    $dom->html->body->div->appendChild($dom->html->body->div->content);
    $dom->html->body->addElement("br");

   
    $dom->html->body->addElement("table", null, array("class"=>"cart infoTable", "id"=>"infoTable"));
    $dom->html->body->table->addElement("tr", null, array("class"=>"cart"))->addElement("th", _("First Name"));
  	$dom->html->body->table->tr->addElement("td", isset($_GET["firstname"])?$_GET["firstname"]:null, array("class"=>"empty"));
  	$dom->html->body->table->addElement("tr", null, array("class"=>"cart"))->addElement("th", _("Last Name"));
  	$dom->html->body->table->tr->addElement("td", isset($_GET["name"])?$_GET["name"]:null, array("class"=>"empty"));
  	$dom->html->body->table->addElement("tr", null, array("class"=>"cart"))->addElement("th", _("Address"));
  	$dom->html->body->table->tr->addElement("td", isset($_GET["address"])?$_GET["address"]:null, array("class"=>"empty"));
  	$dom->html->body->table->addElement("tr", null, array("class"=>"cart"))->addElement("th", _("Postal Code/City"));
  	$dom->html->body->table->tr->addElement("td", isset($_GET["city"])?$_GET["city"]:null, array("class"=>"empty"));
  	$dom->html->body->table->addElement("tr", null, array("class"=>"cart"))->addElement("th", _("E-mail"));
  	$dom->html->body->table->tr->addElement("td", isset($_GET["email"])?$_GET["email"]:null, array("class"=>"empty"));
  	$dom->html->body->table->addElement("tr", null, array("class"=>"cart"))->addElement("th", _("Phone number"));
  	$dom->html->body->table->tr->addElement("td", isset($_GET["phone"])?$_GET["phone"]:null, array("class"=>"empty"));
  	
  	$dom->html->body->addElement("br");
  	$dom->html->body->addElement("br");
  	
    $dom->html->body->addElement("table", null, array("class"=>"cart", "id"=>"cartTable"));
	$dom->html->body->table->addElement("tr", null, array("class"=>"cart"));
  	$dom->html->body->table->tr->addElement("th", _("Item"));
  	$dom->html->body->table->tr->addElement("th", _("Ref."));
  	$dom->html->body->table->tr->addElement("th", _("Price"));
  	$dom->html->body->table->tr->addElement("th", _("Number"));
  	$dom->html->body->table->tr->addElement("th", _("Total"));
  	if(isset($cart)){
	$total=0;
  	foreach($cart as $item=>$num){
  		$curItem=new ShopItem($item);
  		$dom->html->body->table->addElement("tr");
  		$dom->html->body->table->tr->addElement("td", $curItem->name);
  		$dom->html->body->table->tr->addElement("td", $curItem->ref);
  		$dom->html->body->table->tr->addElement("td", $curItem->price." €");
  		$dom->html->body->table->tr->addElement("td", $num." ");
  		$dom->html->body->table->tr->addElement("td", $num*$curItem->price." €");
  		  		$total+=$num*$curItem->price;
  		  		}
  		  		if(Plugin::getParam("shop_shipping")>0){
  		  		$dom->html->body->table->addElement("tr");
  		  		$dom->html->body->table->tr->addElement("td", _("Shipping"), array("colspan"=>4, "class"=>"right"));
  		$dom->html->body->table->tr->addElement("td", Plugin::getParam("shop_shipping")." €");
  		$total+=Plugin::getParam("shop_shipping");
  		}
  		$dom->html->body->table->addElement("tr");
  		$dom->html->body->table->tr->addElement("th", _("Total"), array("colspan"=>4, "class"=>"right"));
  		$dom->html->body->table->tr->addElement("td")->addElement("strong", $total." €");
	}
	
	
	
	if(isset($_GET["pdfButton"])){
		require_once("plugins/".$_GET["pdf"]."/dompdf/dompdf_config.inc.php");

	$dompdf = new DOMPDF();
	$dompdf->load_html($dom->saveHTML());
	$dompdf->render();
	$dompdf->stream($config->title." - "._("Order Form").".pdf");
	}else{
	print($dom->saveHTML());
	}
	
}else{
	Error::$tag=$dom->html->body;
	$dom->html->head->addElement("title", _("You have to agree to the terms of sale!"));
	trigger_error(_("You have to agree to the terms of sale!"), E_USER_WARNING);
	print($dom->saveHTML());
}
?>
