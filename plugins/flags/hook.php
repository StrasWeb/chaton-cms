<?php
    global $dom, $config;
if(isset($this) && $config->multilingual){
    $dom->getElementById("menu")->removeChild($dom->getElementById("chooselang"));
    $dom->header=$dom->getElementById("header");
    $dom->header->addElement("div", null, array("id"=>"flags"));
    foreach($config->languages as $lang=>$value){
        //Il faudra rajouter d'autres drapeaux
    $dom->header->div->addElement("a", null, array("href"=>"index.php?lang=".$lang))->addElement("img", null, array("alt"=>$lang, "src"=>"plugins/".$this->dir."/img/".$lang.".png"));
    $dom->header->div->addElement("span", " ");
    }
}
?>
