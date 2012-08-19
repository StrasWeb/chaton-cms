<?php
if(isset($dom)){
	if(isset($_GET["page"]) && $_GET["page"]=="help"){
	include("help.php");
} else {
    $dom->html->body->div->div->addElement("h2", null, array("class"=>"subtitle"))->addElement("a", _("Shop"), array("href"=>"index.php?tab=plugin&dir=".$_GET["dir"]));
     $dom->html->body->div->div->h2->addElement("form", null, array(), true)->addElement("button", _("Help"), array("type"=>"submit", "class"=>"help-button"));
    $dom->html->body->div->div->h2->form->addElement("input", null, array("type"=>"hidden", "value"=>"plugin", "name"=>"tab"));
	$dom->html->body->div->div->h2->form->addElement("input", null, array("type"=>"hidden", "value"=>$_GET["dir"], "name"=>"dir"));
	$dom->html->body->div->div->h2->form->addElement("input", null, array("type"=>"hidden", "value"=>"help", "name"=>"page"));
    $dom->html->body->div->div->addElement("script", "", array("src"=>"tiny_mce/tiny_mce.js", "type"=>"text/javascript"));
	$dom->html->body->div->div->addElement("script", "", array("src"=>"tiny_mce.js", "type"=>"text/javascript"));
    require_once("ShopItem.php");
    if(isset($_GET["address"])){
    	if($plugin->setParam("shop_address", $_GET["address"])){
    		$dom->html->body->div->div->addElement("div", _("Header successfully updated!"), array("class"=>"modified"));
    	}
    }
    if(isset($_GET["tos"])){
    	if($plugin->setParam("shop_tos", $_GET["tos"])){
    		$dom->html->body->div->div->addElement("div", _("Terms of sale successfully updated!"), array("class"=>"modified"));
    	}
    }
    if(isset($_GET["shipping"])){
    	if($plugin->setParam("shop_shipping", $_GET["shipping"])){
    		$dom->html->body->div->div->addElement("div", _("Shipping cost successfully updated!"), array("class"=>"modified"));
    	}
    }
    if(isset($_POST["order"])){
    	if(isset($_POST["order"])){
    	$items=ShopItem::getAll();
    	foreach($items as $item){
    		$item=new ShopItem($item->id);
    		$item->num=$_POST[$item->id];
    		$item->update();
    	}
    	}
    }
    if(isset($_GET["upload"])){
    $dom->html->body->div->div->addElement("form", null, array("method"=>"POST", "action"=>"?tab=plugin&dir=".$_GET["dir"], "enctype"=>"multipart/form-data"));
     $dom->html->body->div->div->form->addElement("input", null, array("type"=>"radio", "name"=>"type", "value"=>"thumb", "id"=>"thumb", "checked"=>true));
    $dom->html->body->div->div->form->addElement("label", _("Thumbnail"), array("for"=>"thumb"));
    $dom->html->body->div->div->form->addElement("br");
     $dom->html->body->div->div->form->addElement("input", null, array("type"=>"radio", "name"=>"type", "value"=>"image_big", "id"=>"image_big"));
    $dom->html->body->div->div->form->addElement("label", _("Image"), array("for"=>"image_big"));
    $dom->html->body->div->div->form->addElement("br");
       $dom->html->body->div->div->form->addElement("input", null, array("type"=>"file", "name"=>"image"));
    $dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "name"=>"item", "value"=>$_GET["item"]));
    $dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "name"=>"uploaded", "value"=>true));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit"));	
	 }else if(isset($_POST["uploaded"])){	
		if(isset($_FILES["image"])){
			if($_POST["type"]=="thumb"){
				$type="type";
				$suffix="";
			}else if($_POST["type"]=="image_big"){
				$type="type2";
				$suffix="_big";
			}
		$item=new ShopItem($_POST["item"]);
    	$item->$type=$_FILES["image"]["type"];
    	$item->update();
		  if (move_uploaded_file($_FILES["image"]["tmp_name"], getcwd()."/plugins/".$_GET["dir"]."/images/".$item->id.$suffix.".".ShopItem::getExt($item->$type))) {
				$dom->html->body->div->div->addElement("div", _("Image successfully uploaded"), array("class"=>"modified"));
				header ("Refresh: 2;URL=index.php?tab=plugin&dir=".$_GET["dir"]);
			}
		}
	    }else if(isset($_GET["delete"])){
		$item=new ShopItem($_GET["item"]);
		$item->delete();
		header("Location: index.php?tab=plugin&dir=".$_GET["dir"]);
		}else	if(isset($_GET["edited"])){
		$item = new ShopItem($_GET["item"]);
    	$item->name=$_GET["name"];
    	$item->ref=$_GET["ref"];
    	$item->desc=$_GET["desc"];
    	$item->price=$_GET["price"];
    	if($item->update()){
    		$dom->html->body->div->div->addElement("div", _("Item successfully updated!"), array("class"=>"modified"));
    		header ("Refresh: 2;URL=index.php?tab=plugin&dir=".$_GET["dir"]);
    	}
		}else	if(isset($_GET["edit"])){
		$dom->html->body->div->div->addElement("form");
		$item=new ShopItem($_GET["item"]);		
		$dom->html->body->div->div->form->addElement("label", _("Name:")." ", array("for"=>"name"));
		$dom->html->body->div->div->form->addElement("input", null, array("name"=>"name", "id"=>"name", "value"=>$item->name));
		$dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("label", _("Reference:")." ", array("for"=>"ref"));
		$dom->html->body->div->div->form->addElement("input", null, array("name"=>"ref", "id"=>"name", "value"=>$item->ref));
		$dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("label",  _("Description:")." ", array("for"=>"desc"));
		$dom->html->body->div->div->form->addElement("input", null, array("name"=>"desc", "id"=>"desc", "value"=>$item->desc));
		$dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("label",  _("Price:")." ", array("for"=>"price"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"number", "name"=>"price", "id"=>"price", "value"=>$item->price));
		$dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>true, "name"=>"edited"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>"plugin", "name"=>"tab"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>$_GET["dir"], "name"=>"dir"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>$_GET["item"], "name"=>"item"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit"));
		  }else  if(isset($_POST["added"])){
		   $item = new ShopItem();
    	$item->name=$_POST["name"];
    	$item->price=$_POST["price"];
    	$item->desc=$_POST["desc"];
    	$item->ref=$_POST["ref"];
    	if($item->add()){
    		$dom->html->body->div->div->addElement("div", _("Item successfully added!"), array("class"=>"modified"));
    		header ("Refresh: 2;URL=index.php?tab=plugin&dir=".$_GET["dir"]);
    	}
    }else{
    	$dom->html->body->div->div->addElement("form")->addElement("label", _("Order form header:")." ", array("for"=>"address"));
    	$dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("textarea", stripslashes($plugin->getParam("shop_address")), array("name"=>"address"));
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("label", _("Terms of sale:")." ", array("for"=>"tos"));
    	$dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("textarea", stripslashes($plugin->getParam("shop_tos")), array("name"=>"tos"));
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
     $dom->html->body->div->div->form->addElement("label", _("Shipping:")." ", array("for"=>"shipping"));
    $dom->html->body->div->div->form->addElement("input", null, array("type"=>"number", "value"=>$plugin->getParam("shop_shipping"), "name"=>"shipping", "id"=>"shipping", "size"=>2));
     $dom->html->body->div->div->form->addElement("span", "€");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
	$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>"plugin", "name"=>"tab"));
	$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>$_GET["dir"], "name"=>"dir"));
	$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit"));
	$dom->html->body->div->div->addElement("hr");
		$items=ShopItem::getAll();
		$dom->html->body->div->div->addElement("form", null, array("method"=>"POST", "action"=>"?tab=plugin&dir=".$_GET["dir"]));
		$dom->html->body->div->div->form->addElement("ul");
		foreach($items as $item){
			$dom->html->body->div->div->form->ul->addElement("li");
			$dom->html->body->div->div->form->ul->li->addElement("input", null, array("size"=>1, "maxlength"=>2, "value"=>$item->num, "name"=>$item->id));
			$dom->html->body->div->div->form->ul->li->addElement("span", $item->name." [".$item->ref."] "." (".$item->price." €)");
			$dom->html->body->div->div->form->ul->li->addElement("br");
			if(isset($item->type)){
			$dom->html->body->div->div->form->ul->li->addElement("img", null, array("src"=>"../plugins/".$_GET["dir"]."/images/".$item->id.".".ShopItem::getExt($item->type), "width"=>"200"));
			}
			$dom->html->body->div->div->form->ul->li->addElement("br");
			$dom->html->body->div->div->form->ul->li->addElement("a", _("Delete"), array("href"=>"?tab=plugin&dir=".$_GET["dir"]."&item=".$item->id."&delete=1", "class"=>"deleteBtn"));
			$dom->html->body->div->div->form->ul->li->addElement("a", _("Edit title/price/description"), array("href"=>"?tab=plugin&dir=".$_GET["dir"]."&item=".$item->id."&edit=1"));
			$dom->html->body->div->div->form->ul->li->addElement("a", _("Upload image"), array("href"=>"?tab=plugin&dir=".$_GET["dir"]."&item=".$item->id."&upload=1"));
		}
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "name"=>"order", "value"=>true));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit", "value"=>_("Save order")));
		$dom->html->body->div->div->addElement("form", null, array("method"=>"POST", "action"=>"?tab=plugin&dir=".$_GET["dir"], "enctype"=>"multipart/form-data"));
		$dom->html->body->div->div->form->addElement("label", _("Name:")." ", array("for"=>"name"));
		$dom->html->body->div->div->form->addElement("input", null, array("name"=>"name", "id"=>"name"));
		$dom->html->body->div->div->form->addElement("br"); $dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("label", _("Reference:")." ", array("for"=>"ref"));
		$dom->html->body->div->div->form->addElement("input", null, array("name"=>"ref", "id"=>"ref"));
		$dom->html->body->div->div->form->addElement("br"); $dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("label", _("Price:")." ", array("for"=>"price"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"number", "name"=>"price", "id"=>"price"));
		$dom->html->body->div->div->form->addElement("br"); $dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("label", _("Description:")." ", array("for"=>"desc"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"text", "name"=>"desc", "id"=>"desc"));
		$dom->html->body->div->div->form->addElement("br"); $dom->html->body->div->div->form->addElement("br");
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"hidden", "value"=>true, "name"=>"added"));
		$dom->html->body->div->div->form->addElement("input", null, array("type"=>"submit", "value"=>_("Add a product")));
}
}
}
?>
