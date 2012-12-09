<?php
/**
 * jQuery Mobile plugin
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
if (isset($dom)) {
    $dom->html->head->addElement(
            "meta", null, array(
                "name"=>"viewport", "content"=>"width=device-width, initial-scale=1"
            )
        );
    $dom->html->head->addElement(
        "link", null, array(
            "rel"=>"stylesheet",
            "href"=>"plugins/".$this->dir."/jquery.mobile-1.1.0.min.css"
        )
    );
    $dom->html->head->addElement(
        "script", null, array(
            "src"=>"plugins/".$this->dir."/jquery.mobile-1.1.0.min.js"
        )
    );
    //Form does not work with jQuery Mobile :/
    $dom->getElementById("menu")
        ->removeChild($dom->getElementById("search"));
    $articles=$dom->getElementsByTagName("article");
    if (count($articles)>0) {
        for ($i=0; $i<$articles->length; $i++) {
            $art=$articles->item($i);
            $art->addElement("h3", UtfNormal::cleanUp(stripslashes($art->getElementsByTagName("h3")->item(0)->nodeValue)));
            $art->removeChild($art->getElementsByTagName("header")->item(0));

        }
    }
}
?>
