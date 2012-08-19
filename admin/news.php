<?php
/**
 * Admin page with list of articles
 *
 * PHP Version 5.3.6
 * 
 * @category Admin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    $dom->html->body->div->div->addElement(
        "h2", null, array("class"=>"subtitle")
    );
    $dom->html->body->div->div->h2->addElement(
        "form", null, array("action"=>"", "method"=>"get")
    )->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"tab", "value"=>"edit_article"
        )
    );
    $dom->html->body->div->div->h2->form->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"add", "value"=>"true"
        )
    );
    $dom->html->body->div->div->h2->form->addElement(
        "button", _("Add an article"), array("class"=>"add-button")
    );
    $dom->html->body->div->div->h2->addElement("span", _("News"));
    if (isset($_GET["delete"])) {
        $dom->html->body->div->div->addElement(
            "div", _("Article successfully deleted"),
            array("class"=>"modified")
        );   
    } else if (isset($_GET["add"])) {
        $dom->html->body->div->div->addElement(
            "div", _("Article succesfully added"),
            array("class"=>"modified")
        );   
    }
    $dom->html->body->div->div->addElement(
        "div", null, array("class"=>"articles")
    )->addElement(
        "div", _("Choose an article to edit:"),
        array("class"=>"header")
    );
    include_once "classes/Article.php";
    $articles=Article::getList($config->lang);
    if (count($articles)>0) {
        $dom->html->body->div->div->div->addElement("ul");
        foreach ($articles as $article) {
            $dom->html->body->div->div->div->ul->addElement(
                "li"
            )->addElement(
                "a", stripslashes($article->title)." (".date(
                    "d/m/Y", strtotime($article->date)
                ).")",
                array(
                    "href"=>"index.php?tab=edit_article&news=".
                    $article->id."&lang=".$article->lang
                )
            );
            if ($config->multilingual) {
                $dom->html->body->div->div->div->ul->li->addElement(
                    "a", " "._("Translate"),
                    array("class"=>"translate_btn",
                    "href"=>"index.php?tab=edit_article&news=".
                    $article->id."&translate=true")
                );
                $dom->html->body->div->div->div->ul->li->addElement("ul");
                foreach ($config->languages as $key=>$value) {
                    $transArticle=new Article($key, $article->id);
                    if ($transArticle->lang != $article->lang 
                        && isset($transArticle->title)
                    ) {
                        $dom->html->body->div->div->div->ul->li->ul->addElement(
                            "li"
                        )->addElement(
                            "a", $transArticle->title, array(
                                "href"=>"index.php?tab=edit_article&news=".
                                $transArticle->id."&lang=".$transArticle->lang
                            )
                        );
                        $dom->html->body->div->div->div->ul->li->ul->li->addElement(
                            "span", " (".$transArticle->lang.")"
                        );
                    }
                }
            }
        }
    } else {
        $dom->html->body->div->div->div->addElement(
            "p", _("No article for the moment")
        );
    }
}
?>
