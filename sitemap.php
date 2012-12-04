<?php
/**
 * Sitemap
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
header("Content-Type: application/xml; charset=utf-8");
require_once "classes/Config.php";
$config=new Config();
$dom=new DOMDocument("1.0", "utf-8");
require_once "classes/dom-enhancer/DOMElement.php";
$dom->registerNodeClass("DOMElement", "DOMenhancer_DOMElement");
$dom->urlset=$dom->createElement("urlset");
$dom->urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
$dom->appendChild($dom->urlset);
$fullpath=(isset(
    $_SERVER["HTTPS"]
)?"https://":"http://").$_SERVER["HTTP_HOST"].substr(
    $_SERVER["SCRIPT_NAME"], 0, strrpos($_SERVER["SCRIPT_NAME"], "/")+1
);
$lang=isset($_GET["lang"])?$_GET["lang"]:$config->lang;
require_once "classes/Article.php";
if ($articles=Article::getAll($lang)) {
    foreach ($articles as $article) {
        $dom->urlset->addElement("url")->addElement(
            "loc", $fullpath."index.php?page=news&amp;news=".$article->id
        );
    }
}
require_once "classes/Page.php";
if ($pages=Page::getAll($lang)) {
    foreach ($pages as $page) {
        $dom->urlset->addElement("url")->addElement(
            "loc", $fullpath."index.php?page=".$page->id
        );
    }
}


print($dom->saveXML());
?>
