<?php
/**
 * About box hook
 * Displays the box
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
global $config, $pages;
if (isset($this) && (!isset($_GET["news"]) 
    || $config->homepage=="news") && (!isset($_GET["page"]) 
    || $_GET["page"]==$config->homepage) && !isset($_GET["plugin"])
) {
    include_once "plugins/".$this->dir."/AboutBox.php";
    $plugin=new AboutBox($this->dir);
    global $dom;
    if (!empty($plugin->content)) {
        $dom->getElementById("main")->setAttribute("class", "main float");
        $mainwrapper=$dom->getElementById("mainwrapper");
        $mainwrapper->addElement(
            "div", null, array("class"=>"box", "id"=>"about_box", "data-role"=>"footer")
        )
            ->addElement("h2", $plugin->title, array("class"=>"box_header"));
        $mainwrapper->div->addElement("div", null, array("class"=>"text"));
        $mainwrapper->div->div->content=$dom->createDocumentFragment();
        $mainwrapper->div->div->content->appendXML(stripslashes($plugin->content));
        $mainwrapper->div->div->appendChild($mainwrapper->div->div->content);
    }
}
?>
