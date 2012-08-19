<?php
if(isset($this)){
require_once("plugins/".$this->dir."/Feed.php");
$feed=new Feed($this->dir);
if($feed->where=="menu" || (!isset($_GET["news"]) && !isset($_GET["page"]) && !isset($_GET["plugin"]))){
if(!empty($feed->url)){
global $dom;
$rss=new DOMDocument();
$rss->load($feed->url);
$items=$rss->getElementsByTagName("item");
if($feed->num>$items->length){
    $feed->num=$items->length;
}
if($feed->where=="menu"){
$menu=$dom->getElementById("menu");
$menu->addElement("ul");
for($i=0;$i<$feed->num;$i++){
    $menu->ul->addElement("li")->addElement("a", substr($items->item($i)->getElementsByTagName("title")->item(0)->nodeValue, 0, 10), array("href"=>$items->item($i)->getElementsByTagName("link")->item(0)->nodeValue));
    if(strlen($items->item($i)->getElementsByTagName("title")->item(0)->nodeValue)>10){
       $menu->ul->li->a->nodeValue.="…"; 
    }
}
}else if($feed->where=="box"){
    $dom->getElementById("main")->setAttribute("class", "main float");
    $this->title=$rss->getElementsByTagName("title")->item(0)->nodeValue;
    $this->link=$rss->getElementsByTagName("link")->item(0)->nodeValue;
    $mainwrapper=$dom->getElementById("mainwrapper");
$mainwrapper->addElement("div", null, array("class"=>"box", "id"=>"about_box"))->addElement("h2", null, array("class"=>"box_header"))->addElement("a", $this->title, array("href"=>$this->link));
$mainwrapper->div->addElement("div", null, array("class"=>"text"));
$mainwrapper->div->div->addElement("ul");
for($i=0;$i<$feed->num;$i++){
    $mainwrapper->div->div->ul->addElement("li")->addElement("a", stripslashes($items->item($i)->getElementsByTagName("title")->item(0)->nodeValue), array("href"=>$items->item($i)->getElementsByTagName("link")->item(0)->nodeValue));
}

}
}
}
}
?>
