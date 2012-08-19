<?php
/**
 * Admin page with list of pages
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
    include_once "classes/Page.php";
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
        "script", "", array("src"=>"drag.js", "type"=>"text/javascript")
    );
    $dom->html->body->div->div->addElement("h2", null, array("class"=>"subtitle"));
    $dom->html->body->div->div->h2->addElement(
        "form",  null, array("action"=>"index.php", "method"=>"get")
    )->addElement("div")->addElement(
        "input", null, array("type"=>"hidden", "name"=>"tab", "value"=>"edit_page")
    );
    $dom->html->body->div->div->h2->addElement("span", _("Pages"));
    $dom->html->body->div->div->h2->form->div->addElement(
        "input", null, array("type"=>"hidden", "name"=>"add", "value"=>true)
    );
    $dom->html->body->div->div->h2->form->div->addElement(
        "button", _("Add a page"), array("class"=>"add-button")
    );
    if (isset($_GET["delete"])) {
        $dom->html->body->div->div->addElement(
            "div", _("Page successfully deleted"), array("class"=>"modified")
        );   
    } else if (isset($_GET["add"])) {
        $dom->html->body->div->div->addElement(
            "div", _("Page succesfully added"), array("class"=>"modified")
        );   
    }


    if (isset($_POST['modified'])) {
        $pages=Page::getList($config->lang);
        foreach ($pages as $pages) {
            $pages=new Page($pages->lang, $pages->id);
            $pages->pos=$_POST['pos_'.$pages->id];
            $pages->update();
        }
        $dom->html->body->div->div->addElement(
            "div", _("Modifications succesfully saved"),
            array("class"=>"modified")
        );
    }


    $dom->html->body->div->div->addElement(
        "div", null, array("class"=>"pages")
    )->addElement(
        "div", _("Choose a page to edit:"), array("class"=>"header")
    );
    $pages=Page::getList($config->lang);
    if (count($pages)>0) {
        $dom->html->body->div->div->div->addElement(
            "p", null, array("id"=>"drag_text")
        );
        $dom->html->body->div->div->div->addElement(
            "form", null, array(
                "action"=>"index.php?tab=pages", "method"=>"post"
            )
        )->addElement("ul");
        foreach ($pages as $key=>$page) {
            $dom->html->body->div->div->div->form->ul->addElement(
                "li", null, array(
                    "id"=>$key, "class"=>"draggable", "draggable"=>"true"
                )
            );
            $dom->html->body->div->div->div->form->ul->li->addElement(
                "a", $page->title, array(
                    "href"=>"index.php?tab=edit_page&page=".$page->id
                )
            );
            $dom->html->body->div->div->div->form->ul->li->addElement(
                "a", _("Delete"), array(
                    "href"=>"index.php?tab=edit_page&page=".$page->id.
                    "&delete=".true."&lang=".$page->lang,
                    "class"=>"deleteBtn"
                )
            );
            $dom->html->body->div->div->div->form->ul->li->addElement(
                "span", _("Position:")." ", array("class"=>"pos")
            )->addElement(
                "input", null, array(
                    "type"=>"text", "name"=>"pos_".$page->id, "value"=>$page->pos
                )
            );
            if ($config->multilingual) {
                $dom->html->body->div->div->div->form->ul->li->addElement(
                    "a", " "._("Translate"), array(
                        "class"=>"translate_btn",
                        "href"=>"index.php?tab=edit_page&page=".
                        $page->id."&translate=true"
                    )
                );
                $dom->html->body->div->div->div->form->ul->li->addElement("ul");
                foreach ($config->languages as $key=>$value) {
                    $transPage=new Page($key, $page->id);
                    if ($transPage->lang != $page->lang
                        && isset($transPage->title)
                    ) {
                        $dom->html->body->div->div->div
                            ->form->ul->li->ul->addElement(
                                "li"
                            )->addElement(
                                "a", $transPage->title, array(
                                    "href"=>"index.php?tab=edit_page&page=".
                                    $transPage->id.
                                    "&lang=".$transPage->lang
                                )
                            );
                        $dom->html->body->div->div->div->form->ul
                            ->li->ul->li->addElement(
                                "span", " (".$transPage->lang.")"
                            );
                    }
                }
            }
        }
        $dom->html->body->div->div->div->form->ul->addElement(
            "li", null, array("class"=>"save_pos")
        )->addElement(
            "input", null, array(
                "type"=>"submit", "class"=>"pos", "value"=>_("Save position")
            )
        );
        $dom->html->body->div->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"modified", "value"=>true
            )
        );
    } else {
        $dom->html->body->div->div->div->addElement(
            "p",  _("No pages for the moment")
        );
    }
  
}
?>
