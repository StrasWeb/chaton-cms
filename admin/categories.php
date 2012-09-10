<?php
/**
 * Manage categories
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
    $maindiv->addElement("h2", null, array("class"=>"subtitle"));
    $maindiv->h2->addElement(
        "form", null, array("action"=>"index.php?tab=categories", "method"=>"get")
    ); 
    $maindiv->h2->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"tab", "value"=>"add_cat")
    );
    $maindiv->h2->form->addElement(
        "button", _("Add a category"), array("class"=>"add-button")
    ); 
    $maindiv->h2->addElement("span", _("Categories"));
    if (isset($_GET['add'])) {
        trigger_error(_("Link successfully added"), E_USER_NOTICE);
    }
    if ($cats=Category::getAll()) {
        $ul=$maindiv->addElement("ul");
        foreach ($cats as $cat) {
            $ul->addElement("li", $cat->name);
        }
    } else {
        $maindiv->addElement("p", _("No category yet"));
    }
}
?>
