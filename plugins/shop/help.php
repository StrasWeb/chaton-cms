<?php
if(isset($dom)){
$dom->html->body->div->div->addElement("h2", _("Shop")." - "._("Help"), array("class"=>"subtitle"));
$dom->html->body->div->div->addElement("a", _("Back to shop admin"), array("href"=>"http://localhost/chaton-cms/admin/index.php?tab=plugin&dir=".$_GET["dir"]));

$dom->html->body->div->div->addElement("ul");
$dom->html->body->div->div->ul->addElement("li", _("Click on Edit Title/Price/Description to edit the title, the price or the description of a product."));
$dom->html->body->div->div->ul->addElement("li", _("Click on Delete to delete a product."))->addElement("strong", _(" (There is no trash, the picture will be deleted permanently.)"));
$dom->html->body->div->div->ul->addElement("li", _("To add a product, use the form at the bottom of the page."));
}
?>
