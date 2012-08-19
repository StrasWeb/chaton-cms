<?php
/**
 * Form to add an external link
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
    $link=new Link($config->lang);
    $link->title=isset($_POST["link_title"])?$_POST["link_title"]:$link->title;
    $link->url=isset($_POST["link_url"])?$_POST["link_url"]:$link->url;

    $dom->html->body->div->div->addElement(
        "script", "", array(
            "src"=>"tiny_mce/tiny_mce.js", "type"=>"text/javascript"
        )
    );
    $dom->html->body->div->div->addElement(
        "script", "", array("src"=>"tiny_mce.js", "type"=>"text/javascript")
    );

    $dom->html->body->div->div->addElement(
        "h2", _("Add a link"), array("class"=>"subtitle")
    );

    if (isset($_POST["added"])) {
        if (!$_POST["link_title"]) {
            trigger_error(_("Empty title!"), E_USER_WARNING);
        }
        if (!$_POST["link_url"]) {
            trigger_error(_("Empty URL!"), E_USER_WARNING);
        } else if ($_POST["link_title"] && $link->add()) {
            header("Location:index.php?tab=links&add=".true);
        }
    }

    $dom->html->body->div->div->addElement(
        "form", null, array("action"=>"", "method"=>"post")
    )->addElement("label", _("Title:"), array("for"=>"link_title"));
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"text", "id"=>"link_title",
            "name"=>"link_title", "value"=>$link->title
        )
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "label", _("URL:"), array("for"=>"link_url")
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"url", "id"=>"link_url", "name"=>"link_url", "value"=>$link->url
        )
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"added", "value"=>true)
    );
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"submit", "value"=>_("Save"))
    );
}

?>
