<?php
/**
 * HTML <head>
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    include_once "classes/UtfNormal.php";
    $dom->html->setAttribute("lang", $config->lang);
    $dom->html->head->setAttribute("id", "head");
    //$dom->html->head->addElement("meta", null, array("charset"=>"UTF-8"));
    $dom->html->head->addElement(
        "title", stripslashes(htmlspecialchars($config->title))
    );
    $lang=isset($_GET["lang"])?$_GET["lang"]:$config->lang;   
    $dom->html->head->addElement(
        "link", null, array(
            "rel"=>"alternate", "href"=>"rss.php", "type"=>"application/rss+xml",
            "title"=>$config->title." (".$config->lang.")"
        )
    );
    $dom->html->head->addElement(
        "meta", null, array("name"=>"description", "content"=>$config->desc)
    );
    $dom->html->head->addElement(
        "meta", null, array(
            "name"=>"generator", "content"=>"Chaton CMS v".$config->chaton_ver
        )
    );
    

    if ($config->theme) {
        $dom->html->head->addElement(
            "link", null, array("rel"=>"stylesheet",
            "href"=>"inc/base_style.css"
        )
        );
        $dom->html->head->addElement(
            "link", null, array(
                "rel"=>"stylesheet",
                "href"=>"themes/".$config->theme."/theme.css"
            )
        );
        if (file_exists("themes/".$config->theme."/mobile.css")) {
            $dom->html->head->addElement(
                "link", null, array(
                    "rel"=>"stylesheet",
                    "href"=>"themes/".$config->theme."/mobile.css",
                    "media"=>"handheld"
                )
            );
        }
    }




    //JS pour rendre le HTML5 compatible avec IE
    $dom->commentIE=new DOMComment(
        "[if lt IE 9]><script src='js/html5.js'></script><![endif]"
    );
    $dom->html->head->appendChild($dom->commentIE);
    
    //Il faudrait tester s'il y a une extension qui l'utilise, inutile sinon
    if (defined("JQUERY")) {
        if (is_file("/usr/share/javascript/jquery/jquery.min.js")) {
            //On utilise le paquet Debian si possible
            $dom->html->head->addElement(
                "script", null, array("src"=>"/javascript/jquery/jquery.min.js")
            );
        } else {
            $dom->html->head->addElement(
                "script", null, array("src"=>"js/jquery.js")
            );
        }
    }
    
    if (file_exists("favicon.ico")) {
        $dom->html->head->addElement(
            "link", null, array(
                "rel"=>"icon",
                "href"=>"favicon.ico",
            )
        );
    }
}
?>
