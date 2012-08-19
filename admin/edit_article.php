<?php
/**
 * Article edit form
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
        "script", "", array("src"=>"../js/Gettext.js", "type"=>"text/javascript")
    );
    $dom->html->body->div->div->addElement(
        "script", "", array(
            "src"=>"../js/localization.js", "type"=>"text/javascript"
        )
    );

    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"delete.js", "type"=>"text/javascript")
    );
    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"tiny_mce/tiny_mce.js", "type"=>"text/javascript")
    );
    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"tiny_mce.js", "type"=>"text/javascript")
    );

    include_once "classes/Article.php";
    $lang=isset($_POST['def_lang'])?$_POST['def_lang']:$config->lang;
    $lang=isset($_GET['lang'])?$_GET['lang']:$lang;
    $id=isset($_POST['id'])?$_POST['id']:null;
    $id=isset($_GET['news'])?$_GET['news']:$id;

    $article=new Article($lang, $id);
    $article->title=isset($_POST["news_title"])?
    stripslashes($_POST["news_title"]):stripslashes($article->title);
    $article->content=isset($_POST["news_content"])?
    stripslashes($_POST["news_content"]):$article->content;
    $article->date=isset($_POST["news_date"])?$_POST["news_date"]:$article->date;
    $article->lang=isset($_POST["def_lang"])?$_POST["def_lang"]:$article->lang;
    $article->id=isset($_POST["id"])?$_POST["id"]:$article->id;
    if (isset($_GET['add'])) {
        $dom->html->body->div->div->addElement(
            "h2", _("Add an article"), array("class"=>"subtitle")
        );
    } elseif (isset($_GET['translate'])) {
        $dom->html->body->div->div->addElement(
            "h2", _("Translate an article:"), array("class"=>"subtitle")
        )->addElement("span", " - ");
        $dom->html->body->div->div->h2->addElement("em", $article->title);
    } else {
        $dom->html->body->div->div->addElement(
            "h2", _("Edit an article:"), array("class"=>"subtitle")
        )->addElement("span", " - ");
        $dom->html->body->div->div->h2->addElement("em", $article->title);
    }

    if (isset($_POST['delete'])) {
        if ($article->delete()) {
            header("Location:index.php?tab=news&delete=".true);
        }
    } else if (isset($_POST['modified'])
        || isset($_POST['added'])
        || isset($_POST['translated'])
    ) {
        $date_parts=preg_split("/[\s-]+/", $_POST['news_date']);
        if (!$_POST['news_title']) {
            $dom->html->body->div->div->addElement(
                "div", _("Empty title!"), array("class"=>"error")
            );
        } else if (!$_POST['news_content']) {
            $dom->html->body->div->div->addElement(
                "div", _("The article is empty!"), array("class"=>"error")
            );
        } else if (!is_numeric($date_parts[0])
            || !is_numeric($date_parts[1])
            || !is_numeric($date_parts[2])
            || !checkdate($date_parts[1], $date_parts[2], $date_parts[0])
        ) {
            $dom->html->body->div->div->addElement(
                "div", _("Wrong date format!"), array("class"=>"error")
            );
        } else {
            if (isset($_POST['modified'])) {
                if ($article->update()) {
                    $dom->html->body->div->div->addElement(
                        "div", _("Modifications succesfully saved"),
                        array("class"=>"modified")
                    );
                }
            } else if (isset($_POST['added'])) {
                if ($article->add()) {
                    header("Location:index.php?tab=news&add=".true);
                }
            } else if (isset($_POST['translated'])) {
                if ($article->add(true)) {
                    header("Location:index.php?tab=news&add=".true);
                }
            }
        }
    }
        
    $dom->html->body->div->div->addElement(
        "form", null, array("action"=>"", "method"=>"post")
    )->addElement("label", _("Title:"), array("for"=>"news_title"));
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"text", "id"=>"news_title", "name"=>"news_title",
            "value"=>$article->title
        )
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");

    if (isset($_GET['translate'])) {
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"news_date",
                "value"=>$article->date
            )
        );
    } else {
        /*Il faudrait que quand on change la date,
         * cela change aussi celle des traductions*/
        $dom->html->body->div->div->form->addElement(
            "label", _("Date:"), array("for"=>"news_date")
        )->addElement(
            "span", " ("._("YYYY-MM-DD").")", array("class"=>"small")
        );
        $dom->html->body->div->div->form->addElement("br");
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"date", "id"=>"news_date",
                "name"=>"news_date", "value"=>$article->date
            )
        );
        $dom->html->body->div->div->form->addElement("br");
        $dom->html->body->div->div->form->addElement("br");
    }
    $dom->html->body->div->div->form->addElement(
        "label", _("Content:"), array("for"=>"news_content")
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "textarea", null, array(
            "rows"=>"25", "cols"=>"25", "id"=>"news_content",
            "name"=>"news_content"
        )
    );
    if (!empty($article->content)) {
        $dom->html->body->div->div->form->textarea
            ->content=$dom->createDocumentFragment();
        $dom->html->body->div->div->form->textarea->content->appendXML(
            stripslashes($article->content)
        );
        $dom->html->body->div->div->form->textarea->appendChild(
            $dom->html->body->div->div->form->textarea->content
        );
    }
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    if (isset($_GET['add'])) {
            $dom->html->body->div->div->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"added", "value"=>true
                )
            );
            $dom->html->body->div->div->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"def_lang",
                    "value"=>$config->lang
                )
            );

    } else {
        if (isset($_GET['translate'])) {
            $dom->html->body->div->div->form->addElement(
                "label", _("Language:")." ", array("for"=>"def_lang")
            );
            $dom->html->body->div->div->form->addElement(
                "select", null, array("id"=>"def_lang", "name"=>"def_lang")
            );
            foreach ($config->languages as $key=>$value) {
                if ($key!=$article->lang) {
                    $dom->html->body->div->div->form->select->addElement(
                        "option", $languageCodes[$key], array("value"=>$key)
                    );

                }
            }
            $dom->html->body->div->div->form->addElement("br");
            $dom->html->body->div->div->form->addElement("br");
        } else {
            $dom->html->body->div->div->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"def_lang", "value"=>$article->lang
                )
            );

        }
        if (isset($_GET['translate'])) {
            $dom->html->body->div->div->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"translated", "value"=>true
                )
            );

        } else {
            $dom->html->body->div->div->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"modified", "value"=>true
                )
            );

        }
    }
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"id", "value"=>$article->id
        )
    );
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"old-lang", "value"=>$article->lang
        )
    );
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"submit", "value"=>_("Save"))
    );
    if (!isset($_GET['add']) && !isset($_GET['translate'])) {
        $dom->html->body->div->div->addElement(
            "form", null, array(
                "action"=>"", "method"=>"post", "id"=>"deleteForm"
            )
        )->addElement(
            "input", null, array(
                "class"=>"deleteBtn", "type"=>"submit",
                "value"=>_("Delete this article")
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"delete", "id"=>"deleteHidden",
                "value"=>true
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"lang", "value"=>$article->lang
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"id", "value"=>$article->id
            )
        );
    }
}
?>
