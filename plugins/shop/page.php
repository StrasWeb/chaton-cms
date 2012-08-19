<?php
if(isset($this)) {
  global $dom, $config;
  $dom->html->head->addElement("link",null,array("rel"=>"stylesheet","href"=>"plugins/".$this->dir."/style.css"));
  $dom->html->body->div->div->section->addElement("div",null,array("class"=>"text","id"=>$this->dir."Page"));
  $this->wrapper=$dom->getElementById("wrapper");
  require_once("ShopItem.php");
  if(isset($_GET["cart"])) {
  	if(isset($_COOKIE["cart"])){
    		$this->cart=unserialize(stripslashes($_COOKIE["cart"]));
    		}
  	if(isset($_GET["add"])){
  		$this->cart[$_GET["add"]]=isset($this->cart[$_GET["add"]])?$this->cart[$_GET["add"]]+1:1;
  		setcookie("cart", serialize($this->cart));
  		header("Location: index.php?plugin=".$this->dir."&lang=fr&cart=1");
  	}else if(isset($_GET["less"])){
		$this->cart[$_GET["less"]]-=1;
		if($this->cart[$_GET["less"]]<=0){
			unset($this->cart[$_GET["less"]]);
		}
		setcookie("cart", serialize($this->cart));
		  header("Location: index.php?plugin=".$this->dir."&lang=fr&cart=1");
	}else if(isset($_GET["del"])){
		unset($this->cart[$_GET["del"]]);
		setcookie("cart", serialize($this->cart));
		 header("Location: index.php?plugin=".$this->dir."&lang=fr&cart=1");
	}
	$dom->html->body->div->div->section->div->addElement("a", _("Back to shop"), array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang, "class"=>"back"));
  	$dom->html->body->div->div->section->div->addElement("table", null, array("class"=>"cart", "id"=>"cartTable"));
  	$dom->html->body->div->div->section->div->table->addElement("tr", null, array("class"=>"cart"));
  	$dom->html->body->div->div->section->div->table->tr->addElement("th", _("Item"));
  	$dom->html->body->div->div->section->div->table->tr->addElement("th", _("Ref."));
  	$dom->html->body->div->div->section->div->table->tr->addElement("th", _("Price"));
  	$dom->html->body->div->div->section->div->table->tr->addElement("th", _("Number"));
  	$dom->html->body->div->div->section->div->table->tr->addElement("th", _("Total"));
  	if(isset($this->cart)){
	$this->total=0;
  	foreach($this->cart as $item=>$num){
  		$curItem=new ShopItem($item);
  		$dom->html->body->div->div->section->div->table->addElement("tr");
  		$dom->html->body->div->div->section->div->table->tr->addElement("td")->addElement("a", null, array("class"=>"deleteLink", "href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&del=".$curItem->id."&cart=".true))->addElement("img", null, array("alt"=>_("Delete"), "src"=>"plugins/".$this->dir."/img/del.png"));
  		$dom->html->body->div->div->section->div->table->tr->td->addElement("a", $curItem->name, array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&item=".$curItem->id));
  		$dom->html->body->div->div->section->div->table->tr->addElement("td", $curItem->ref);
  		$dom->html->body->div->div->section->div->table->tr->addElement("td", $curItem->price." €");
  		$dom->html->body->div->div->section->div->table->tr->addElement("td", $num." ")->addElement("a", "-", array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&less=".$curItem->id."&cart=".true, "title"=>_("Less"), "class"=>"less quantity"));
  		$dom->html->body->div->div->section->div->table->tr->td->addElement("span", " ");
  		$dom->html->body->div->div->section->div->table->tr->td->addElement("a", "+", array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&add=".$curItem->id."&cart=".true, "title"=>_("More"), "class"=>"more quantity"));
  		$dom->html->body->div->div->section->div->table->tr->addElement("td", $num*$curItem->price." €");
  		  		$this->total+=$num*$curItem->price;

  	}
  	if(Plugin::getParam("shop_shipping")>0){
  	     $dom->html->body->div->div->section->div->table->addElement("tr");
  		$dom->html->body->div->div->section->div->table->tr->addElement("td", _("Shipping"), array("colspan"=>4, "class"=>"right"));
  		$dom->html->body->div->div->section->div->table->tr->addElement("td", Plugin::getParam("shop_shipping")." €");
  		$this->total+=Plugin::getParam("shop_shipping");
  		}
  		$dom->html->body->div->div->section->div->table->addElement("tr");
  		$dom->html->body->div->div->section->div->table->tr->addElement("th", _("Total"), array("colspan"=>4, "class"=>"right"));
  		$dom->html->body->div->div->section->div->table->tr->addElement("td")->addElement("strong", $this->total." €");
  		$dom->html->body->div->div->section->div->addElement("form", null, array("id"=>"cartLinks", "action"=>"plugins/".$this->dir."/order.php", "method"=>"get", "target"=>"_blank"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"checkbox", "name"=>"accept", "id"=>"accept", "required"=>"required"));
  		$dom->html->body->div->div->section->div->form->addElement("label", _("I agree to the "), array("for"=>"accept"))->addElement("a", _("terms of sale"), array("href"=>"plugins/".$this->dir."/tos.php?dir=".$this->dir."&lang=".$config->lang, "target"=>"_blank"));
  		$dom->html->body->div->div->section->div->form->label->addElement("span", ".");
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("label", _("Name:")." ", array("for"=>"name", "class"=>"label_info"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("name"=>"name", "class"=>"input_info"));
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("label", _("First Name:")." ", array("for"=>"firstname", "class"=>"label_info"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("name"=>"firstname", "class"=>"input_info"));
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("label", _("Address:")." ", array("for"=>"address", "class"=>"label_info"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("name"=>"address", "class"=>"input_info"));
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("label", _("Postal Code/City:")." ", array("for"=>"city", "class"=>"label_info"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("name"=>"city", "class"=>"input_info"));
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("label", _("E-mail:")." ", array("for"=>"email", "class"=>"label_info"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("name"=>"email", "class"=>"input_info", "type"=>"email"));
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("label", _("Phone number:")." ", array("for"=>"phone", "class"=>"label_info"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("name"=>"phone", "class"=>"input_info", "type"=>"tel"));
  		$dom->html->body->div->div->section->div->form->addElement("br");
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"hidden", "value"=>$this->dir, "name"=>"plugin"));
  		$dom->html->body->div->div->section->div->form->addElement("input", null, array("type"=>"submit", "value"=>_("Print order form"), "id"=>"printLink"));
  		

  	}
  }else if(isset($_GET["item"])){
  $this->item=new ShopItem($_GET["item"]);
  	$dom->html->body->div->div->section->div->addElement("div",null,array("class"=>"item"))->addElement("img",null,array("src"=>"plugins/".$this->dir."/images/".$this->item->id."_big.".ShopItem::getExt($this->item->type2),"class"=>"image","id"=>$this->item->id));
  	$dom->html->body->div->div->section->div->div->addElement("h2",$this->item->name);
      $dom->html->body->div->div->section->div->div->addElement("big",$this->item->price." €", array("class"=>"price"));
      $dom->html->body->div->div->section->div->div->addElement("p",$this->item->desc, array("class"=>"desc"));
      $dom->html->body->div->div->section->div->div->addElement("a", _("Add to cart"), array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&add=".$this->item->id."&cart=".true));
  }else{
  $this->wrapper->setAttribute("class",$this->wrapper->getAttribute("class")." shoplist");
  $this->items=ShopItem::getAll();
  $dom->html->body->div->div->section->div->addElement("div", null, array("class"=>"itemList"));
  if(count($this->items>1)) {
    foreach($this->items as $item) {
      $dom->html->body->div->div->section->div->div->addElement("div",null,array("class"=>"item"))->addElement("a", null, array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&item=".$item->id))->addElement("img",null,array("src"=>"plugins/".$this->dir."/images/".$item->id.".".ShopItem::getExt($item->type),"width"=>300,"class"=>"image","id"=>$item->id));
      $dom->html->body->div->div->section->div->div->div->a->addElement("h4",$item->name);
      $dom->html->body->div->div->section->div->div->div->addElement("span",$item->price." €", array("class"=>"price"));
    }
  }
   $dom->html->body->div->div->section->div->addElement("a", _("Cart"), array("class"=>"cart", "href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang."&cart=".true));
}
}
?>
