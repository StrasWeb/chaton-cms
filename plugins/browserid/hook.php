<?php
/**
 * BrowserID hook
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
global $dom;
$dom->head->addElement(
    "script", null, array("src"=>"https://browserid.org/include.js")
);
$dom->head->addElement(
    "script", null, array("src"=>"plugins/".$this->dir."/browserid.js")
);
$dom->head->addElement(
    "link", null,
    array("rel"=>"stylesheet", "href"=>"plugins/".$this->dir."/style.css")
);


$dom->getElementById("footer")->childNodes->item(0)->addSpace();
$dom->getElementById("footer")->childNodes->item(0)->addElement(
    "img", null, array(
        "src"=>"https://login.persona.org/i/sign_in_blue.png",
        "id"=>"browserID", "class"=>"browserID", "alt"=>"BrowserID"
    )
);
?>
