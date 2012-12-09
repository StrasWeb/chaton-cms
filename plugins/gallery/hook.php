<?php
/**
 * Gallery plugin hook
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($this)) {
    global $dom, $config;
    $this->title=$this->getParam("gallery_title");
    if (empty($this->title)) {
        $this->title=_("Gallery");
    }
    $dom->getElementById("pluginMenu")
        ->addElement("li", null, array("id"=>$this->dir."MenuItem"))
        ->addElement(
            "a", $this->title,
            array(
                "href"=>"index.php?plugin=".$this->dir."&lang=".$config->lang,
                "data-theme"=>"a"
            )
        );
}
?>
