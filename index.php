<?php
/**
 * Index file
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
/*
 * Chez Free.fr, le fichier doit être renommé en .php5,
 * mais du coup certains liens ne marchent plus...
 * */
if (!is_file("config.php")) {
    header('Location: install.php');
} else {
    include_once "classes/Config.php";
    $config=new Config();
    
    include_once "classes/Plugin.php";
    $plugins=Plugin::getList();
    
    include "inc/localization.php";
    //Essayons de garder les pages valides (HTML5).
    include_once "classes/dom-enhancer/XMLDocument.php";
    $doc=new DOMenhancer_XMLDocument();
    $dom=$doc->DOM;
    include "inc/version.php";
    
    foreach ($plugins as $dir) {
        $plugin=new Plugin($dir);
        if ($plugin->enabled) {
            $plugin->preHook();
        }
    }
    
    include "inc/head.php";
    $dom->html->body->addElement("div", null, array("id"=>"wrapper"));
    include "inc/header.php";
    $mainsection=$dom->html->body->div->div->addElement(
        "section", null, array(
            "id"=>"main", "class"=>"main", "data-role"=>"content"
        )
    );
    include_once "classes/Article.php";
    $lang=$config->multilingual?$config->lang:null;
    $articles=Article::getList($lang);
    if (isset($_GET["plugin"])) {
        $curPage="plugin_".$_GET["plugin"];
        include_once "classes/Plugin.php";
        $plugin=new Plugin($_GET["plugin"]);
        $plugin->displayPage();
    } elseif (isset($_GET["page"])) {
        if ($_GET["page"]=="news") {
            $curPage="news";
            include "inc/news.php";
        } else {
            $curPage="page_".$_GET["page"];
            $page=new Page($config->lang, $_GET["page"]);
            include "inc/page.php";
        }
    } elseif ($config->homepage=="news") {
        $curPage="news";
        $dom->getElementById("newsMenu")
            ->firstChild->setAttribute("class", "current");
        include "inc/news.php";
    } else {
        $dom->getElementById("homepageMenu")
            ->firstChild->setAttribute("class", "current");
        $curPage="page_".$config->homepage;
        $page=new Page($config->lang, $config->homepage);
        include "inc/page.php";
    }
    $dom->html->body->div->setAttribute(
        "class", $dom->html->body->div->getAttribute("class")." ".$curPage." wrapper"
    );
    if (count($articles)==0&&count($pages)>0) {
        $dom->getElementById("newsMenu")
            ->removeChild($dom->getElementById("newsMenuItem"));
    }
    include "inc/footer.php";
    
    foreach ($plugins as $dir) {
        $plugin=new Plugin($dir);
        if ($plugin->enabled) {
            $plugin->hook();
        }
    }
    
    $mainwrapper=$dom->getElementById("mainwrapper");
    $mainwrapper->addElement("div", null, array("class"=>"clear"));
    print($dom->saveHTML());
}
?>
