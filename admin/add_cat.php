<?php
/**
 * Add a category
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
    include_once "classes/Category.php";
    $cat=new Category();
    $cat->title=isset($_POST["cat_title"])?$_POST["cat_title"]:$cat->title;

    $dom->html->body->div->div->addElement(
        "h2", _("Add a category"), array("class"=>"subtitle")
    );

    if (isset($_POST["added"])) {
        if (!$_POST["cat_title"]) {
            trigger_error(_("Empty title!"), E_USER_WARNING);
        } else if ($_POST["cat_title"] && $cat->add()) {
            header("Location:index.php?tab=categories&add=".true);
        }
    }

    $dom->html->body->div->div->addElement(
        "form", null, array("action"=>"", "method"=>"post")
    )->addElement("label", _("Title:"), array("for"=>"cat_title"));
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array(
            "type"=>"text", "id"=>"cat_title",
            "name"=>"cat_title", "value"=>$cat->title
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
