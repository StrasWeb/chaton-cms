<?php
if(isset($this)){
    global $dom, $config;
    $dom->getElementById("pluginMenu")->addElement("li", null, array("id"=>$this->dir."MenuItem"))->addElement("a", _("Guestbook"), array("href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang));
}
?>
