<?php
/**
 * RSS feed
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
header("Content-Type: application/rss+xml; charset=utf-8");
require_once "classes/Config.php";
$config=new Config();
require "inc/version.php";
$dom=new DOMDocument("1.0", "utf-8");
require_once "classes/dom-enhancer/DOMElement.php";
$dom->registerNodeClass("DOMElement", "DOMenhancer_DOMElement");
$dom->rss=$dom->createElement("rss");
$dom->rss->setAttribute("xmlns:atom", "http://www.w3.org/2005/Atom");
$dom->rss->setAttribute("xmlns:dc", "http://purl.org/dc/elements/1.1/");
$dom->appendChild($dom->rss);
$dom->rss->setAttribute("version", "2.0");
$fullpath=(isset(
    $_SERVER["HTTPS"]
)?"https://":"http://").$_SERVER["HTTP_HOST"].substr(
    $_SERVER["SCRIPT_NAME"], 0, strrpos($_SERVER["SCRIPT_NAME"], "/")+1
);
$dom->rss->addElement("channel")->addElement(
    "atom:link", null, array(
        "href"=>$fullpath."rss.php", "rel"=>"self",
        "type"=>"application/rss+xml"
    )
);
$dom->rss->channel->addElement("title", $config->title);
$dom->rss->channel->addElement("description", htmlspecialchars($config->desc));
$dom->rss->channel->addElement("link", $fullpath."index.php");
$dom->rss->channel->addElement("generator", "Chaton CMS v".$config->chaton_ver);
$dom->rss->channel->addElement("docs", "http://www.rssboard.org/rss-specification");
$lang=isset($_GET["lang"])?$_GET["lang"]:$config->lang;
require_once "classes/Article.php";
if ($articles=Article::getAll($lang)) {
    foreach ($articles as $article) {
        $dom->rss->channel->addElement("item")->addElement(
            "title", stripslashes($article->title)
        );
        $dom->rss->channel->item->addElement("dc:language", $article->lang);
        $dom->rss->channel->item->addElement(
            "link", $fullpath."index.php?page=news&news=".$article->id
        );
        $dom->rss->channel->item->addElement(
            "pubDate", date(DATE_RSS, strtotime($article->date))
        );
        $dom->rss->channel->item->addElement(
            "guid", $fullpath."index.php?page=news&news=".$article->id,
            array("isPermaLink"=>"true")
        );
        $dom->rss->channel->item->addElement(
            "description", stripslashes($article->content)
        );
        
    }
}


print($dom->saveXML());
?>
