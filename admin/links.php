<?php
/**
 * Admin page with list of external links
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
    include_once "classes/Link.php";
    $dom->html->body->div->div->addElement("h2", null, array("class"=>"subtitle"));
    $dom->html->body->div->div->h2->addElement(
        "form", null, array("action"=>"index.php?tab=links", "method"=>"get")
    ); 
    $dom->html->body->div->div->h2->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"tab", "value"=>"add_link")
    );
    $dom->html->body->div->div->h2->form->addElement(
        "button", _("Add a link"), array("class"=>"add-button")
    ); 
    $dom->html->body->div->div->h2->addElement("span", _("External links"));

    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"../js/Gettext.js", "type"=>"text/javascript")
    );
    $dom->html->body->div->div->addElement(
        "script", "", array(
            "src"=>"../js/localization.js", "type"=>"text/javascript"
        )
    );

    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"drag.js", "type"=>"text/javascript")
    );

    if (isset($_GET['delete'])) {
        $link=new Link($_GET['linkLang'], $_GET['id']);
        if ($link->delete()) {
            trigger_error(_("Link successfully deleted"), E_USER_NOTICE);
        }
    } else if (isset($_GET['add'])) {
        trigger_error(_("Link successfully added"), E_USER_NOTICE);
    } else if (isset($_POST['modified'])) {
        $links=Link::getList();
        foreach ($links as $link) {
            $link=new Link($config->lang, $link->id);
            $link->pos=$_POST['pos_'.$link->id];
            $link->update();
        }
        trigger_error(_("Modifications succesfully saved"), E_USER_NOTICE);
    }

    $dom->html->body->div->div->addElement("div", null, array("class"=>"links"));
    $links=Link::getList();
    if (count($links)>0) {
        $dom->html->body->div->div->div->addElement(
            "p", null, array("id"=>"drag_text", "class"=>"drag_text")
        );
        $dom->html->body->div->div->div->addElement(
            "form", null, array("action"=>"index.php?tab=links", "method"=>"post")
        )->addElement("ul");
        foreach ($links as $key=>$link) {
            $dom->html->body->div->div->div->form->ul->addElement(
                "li", null, array(
                    "id"=>$key, "class"=>"draggable", "draggable"=>"true"
                )
            )->addElement(
                "span", _("Position:")." ", array("class"=>"pos")
            )->addElement(
                "input", null, array(
                    "type"=>"text", "name"=>"pos_".$link->id, "value"=>$link->pos
                )
            );
            $dom->html->body->div->div->div->form->ul->li->addElement(
                "a", $link->title, array("href"=>$link->url)
            );
            $dom->html->body->div->div->div->form->ul->li->addElement("span", " - ");
            $dom->html->body->div->div->div->form->ul->li->addElement(
                "a", _("Delete"), array(
                    "class"=>"delete",
                    "href"=>"index.php?tab=links&delete=".true.
                    "&id=".$link->id."&linkLang=".$link->lang
                )
            );

        }
        $dom->html->body->div->div->div->form->ul->addElement(
            "li", null, array("class"=>"save_pos")
        )->addElement(
            "input", null, array("type"=>"submit",
            "class"=>"pos", "value"=>_("Save position"))
        );
        $dom->html->body->div->div->div->form->addElement(
            "input", null, array("type"=>"hidden", "name"=>"modified", "value"=>true)
        );
    } else {
        $dom->html->body->div->div->div->addElement(
            "p", _("No links for the moment")
        ); 
    }


}
?>
