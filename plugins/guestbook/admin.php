<?php
if(isset($dom)){
    $dom->html->body->div->div->addElement("h2", _("Guestbook"), array("class"=>"subtitle"));
    require_once("GBComment.php");
    if(isset($_GET["delete"])){
        $comment=new GBComment($_GET["delete"]);
        $comment->delete();
    }
    $comments=GBComment::getAll();
    foreach($comments as $comment){
            $dom->html->body->div->div->addElement("div", null, array("class"=>"comment"));
            $dom->html->body->div->div->div->addElement("a", null, array("href"=>"index.php?tab=plugin&dir=".$_GET["dir"]."&delete=".$comment->id, "title"=>_("Delete")))->addElement("img", null, array("alt"=>_("Delete"), "src"=>"../plugins/".$_GET["dir"]."/img/delete.svg", "height"=>20, "align"=>"top"));
            $dom->html->body->div->div->div->addElement("span", " ".$comment->comment);
            $dom->html->body->div->div->div->addElement("br");
            $dom->html->body->div->div->div->addElement("br");
        }
}
?>
