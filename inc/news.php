<?php
/**
 * Display the news
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
    include_once "classes/Article.php";
    include_once "classes/UtfNormal.php";
    if (isset($_GET["news"])) {
        include_once "classes/Article.php";
        $lang=isset($_GET["lang"])?$_GET["lang"]:$config->lang;   
        $article=new Article($lang, $_GET["news"]);
        if (isset($article->id)) {
            $dom->html->head->title
                ->nodeValue.=" - ".UtfNormal::cleanUp($article->title); 
            $dom->html->body->div->div->section->addElement(
                "article", null, array(
                    "lang"=>$article->lang, "itemscope"=>null,
                    "itemtype"=>"http://schema.org/Article"
                )
            );
            $dom->html->body->div->div->section->article->addElement(
                "h2", null, array(
                    "class"=>"page-title ui-grid-a", "itemprop"=>"name"
                )
            )->addElement(
                "span", UtfNormal::cleanUp($article->title),
                array("class"=>"ui-block-a")
            );
            $dom->html->body->div->div->section->article->h2->addElement(
                "time", date("d/m/Y", strtotime($article->date)), 
                array(
                    "datetime"=>$article->date,"class"=>"date ui-block-b",
                    "itemprop"=>"datePublished"
                )
            );
            $dom->html->body->div->div->section->article->addElement(
                "div", null, array("class"=>"text", "itemprop"=>"articleBody")
            );
            $dom->html->body->div->div->section->article->div
                ->content=$dom->createDocumentFragment();
            $dom->html->body->div->div->section->article->div
                ->content->appendXML(
                    UtfNormal::cleanUp(stripslashes($article->content))
                );
            $dom->html->body->div->div->section->article->div
                ->appendChild(
                    $dom->html->body->div->div->section->article->div->content
                );
        } else {
            include "404.php";
        }
    } else {
        $articles=Article::getAll($config->lang);
        $dom->html->body->div->div->section->addElement(
            "h2", $config->news_title, array(
                "class"=>"page-title news-title"
            )
        );
        if (count($articles)>0) {
            foreach ($articles as $art) {
                $dom->html->body->div->div->section->addElement(
                    "article", null,
                    array(
                        "class"=>"news","lang"=>$art->lang, "itemscope"=>null,
                        "itemtype"=>"http://schema.org/Article"
                    )
                )->addElement("header", null, array("class"=>"header ui-grid-a"));
                $dom->html->body->div->div->section->article->header->addElement(
                    "h3", null, array(
                        "class"=>"subtitle ui-block-a", "itemprop"=>"name"
                    )
                )->addElement(
                    "a", UtfNormal::cleanUp(stripslashes($art->title)),
                    array(
                        "href"=>"index.php?page=news&news=".
                        $art->id."&lang=".$art->lang, 
                        "itemprop"=>"url"
                    )
                );
                $dom->html->body->div->div->section->article->header->addElement(
                    "time", date("d/m/Y", strtotime($art->date)),
                    array(
                        "class"=>"date ui-block-b", "datetime"=>$art->date,
                        "itemprop"=>"datePublished"
                    )
                );
                $dom->html->body->div->div->section->article->addElement(
                    "div", null, array("class"=>"text", "itemprop"=>"articleBody")
                );
                $dom->html->body->div->div->section->article->div
                    ->content=$dom->createDocumentFragment();
                $dom->html->body->div->div->section->article->div
                    ->content->appendXML(
                        UtfNormal::cleanUp(stripslashes($art->content))
                    );
                $dom->html->body->div->div->section->article->div
                    ->appendChild(
                        $dom->html->body->div->div->section->article->div->content
                    );
            }
            $pagenum=$mainsection->addElement("div");
            if ($config->min > 0) {
                $pagenum->addElement(
                    "a", "<", 
                    array(
                        "title"=>_("Previous page"),
                        "href"=>"index.php?min=".($_GET["min"]-$config->perpage)
                    )
                );
            }
            $pagenum->addSpace();
            $pagenum->addElement("b", $config->current);
            $pagenum->addSpace();
            if (Article::getNum() > $config->min+$config->perpage) {
                $pagenum->addElement(
                    "a", ">", 
                    array(
                        "title"=>_("Next page"),
                        "href"=>"index.php?min=".($config->min+$config->perpage)
                    )
                );
            }
        } else {
            $dom->html->body->div->div->section->addElement(
                "div", _("No article for now")
            );
        }
    }
}
?>
