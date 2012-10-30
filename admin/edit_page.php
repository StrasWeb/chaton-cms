<?php
/**
 * Page edit form
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
        "script", "", array(
            "src"=>"../js/Gettext.js", "type"=>"text/javascript"
        )
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
        "script", "", array(
            "src"=>"tiny_mce/tiny_mce.js", "type"=>"text/javascript"
        )
    );
    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"tiny_mce.js", "type"=>"text/javascript")
    );

    include_once "classes/Page.php";
    $lang=isset($_POST['def_lang'])?$_POST['def_lang']:$config->lang;
    $lang=isset($_GET['lang'])?$_GET['lang']:$lang;
    $id=isset($_POST['id'])?$_POST['id']:null;
    $id=isset($_GET['page'])?$_GET['page']:$id;

    $page=new Page($lang, $id);
    $page->title=isset($_POST["page_title"])?
    stripslashes($_POST["page_title"]):$page->title;
    $page->content=isset($_POST["page_content"])?
    stripslashes($_POST["page_content"]):$page->content;
    $page->lang=isset($_POST["def_lang"])?$_POST["def_lang"]:$page->lang;
    $page->id=isset($_POST["id"])?$_POST["id"]:$page->id;

    if (isset($_GET["add"])) {
        $dom->html->body->div->div->addElement(
            "h2", _("Create a page"), array("class"=>"subtitle")
        );
    } elseif (isset($_GET["translate"])) {
            $dom->html->body->div->div->addElement(
                "h2", _("Translate a page"), array("class"=>"subtitle")
            )->addElement("span", " - ");
            $dom->html->body->div->div->h2->addElement("em", $page->title);
    } else {
        $dom->html->body->div->div->addElement(
            "h2", _("Edit a page"), array("class"=>"subtitle")
        )->addElement("span", " - ");
        $dom->html->body->div->div->h2->addElement("em", $page->title);
    }

    if (isset($_POST['delete']) || isset($_GET['delete'])) {
        if ($page->delete()) {
            header("Location:index.php?tab=pages&delete=".true);
        }
    } else if (isset($_POST['modified'])
        || isset($_POST['added'])
        || isset($_POST['translated'])
    ) {
        $page->show=isset($_POST["show"]);
        if (!$_POST['page_title']) {
                $dom->html->body->div->div->addElement(
                    "div", _("Empty title!"), array("class"=>"error")
                );
                $_GET['add']=true;
        } else if (!$_POST['page_content']) {
            $dom->html->body->div->div->addElement(
                "div", _("The page is empty!"), array("class"=>"error")
            );
            $_GET['add']=true;
        } else {
            if (isset($_POST['modified'])) {
                if ($page->update()) {
                    $dom->html->body->div->div->addElement(
                        "div", _("Modifications succesfully saved"),
                        array("class"=>"modified")
                    );
                }
            } else if (isset($_POST['added'])) {
                if ($page->add()) {
                    header("Location:index.php?tab=pages&add=".true);
                }
            } else if (isset($_POST['translated'])) {
                if ($page->add(true)) {
                    header("Location:index.php?tab=pages&add=".true);
                }
            }
            
        }
        
    }    
    $dom->html->body->div->div->addElement(
        "form", null, array(
            "action"=>"index.php?tab=edit_page", "method"=>"post"
        )
    );
    $dom->html->body->div->div->form->addElement(
        "label", _("Title:"), array("for"=>"page_title")
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"text", "id"=>"page_title",
            "name"=>"page_title", "value"=>$page->title
        )
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "label", _("URL:"), array("for"=>"page_url")
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"text", "id"=>"page_url",
            "name"=>"page_url",
            "value"=>$config->fullpath."index.php?page=".$page->id."&lang=".$page->lang,
            "readonly"=>"readonly"
        )
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "label", _("Content:"), array("for"=>"page_content")
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "textarea", null, array(
            "rows"=>"25", "cols"=>"25",
            "id"=>"page_content", "name"=>"page_content"
        )
    );
    if (!empty($page->content)) {
        $dom->html->body->div->div->form->textarea
            ->content=$dom->createDocumentFragment();
        $dom->html->body->div->div->form->textarea->content
            ->appendXML($page->content);
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
            "input", null, array("type"=>"hidden", "name"=>"def_lang",
            "value"=>$config->lang)
        );
        
    } elseif (isset($_GET['translate'])) {
        $dom->html->body->div->div->form->addElement(
            "label", _("Language:")." ", array("for"=>"def_lang")
        );
        $dom->html->body->div->div->form->addElement(
            "select", null, array("id"=>"def_lang", "name"=>"def_lang")
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"translated", "value"=>true
            )
        );
        foreach ($config->languages as $key=>$value) {
            if ($key!=$page->lang) {
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
                "type"=>"hidden", "name"=>"modified", "value"=>true
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"def_lang", "value"=>$page->lang
            )
        );
    }
    
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"checkbox", "id"=>"show", "name"=>"show")
    );
    if (isset($page->show) && $page->show) {
        $dom->html->body->div->div->form->input
            ->setAttribute("checked", "checked");
    }
    $dom->html->body->div->div->form->addElement(
        "label", _("Show page in menu"), array("for"=>"show")
    );
    
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"id", "value"=>$page->id
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
                "value"=>_("Delete this page")
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"delete",
                "id"=>"deleteHidden", "value"=>true
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"lang", "value"=>$page->lang
            )
        );
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "type"=>"hidden", "name"=>"id", "value"=>$page->id
            )
        );
    }
}
?>
