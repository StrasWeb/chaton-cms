<?php
/**
 * Gallery plugin
 * Help page
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    $dom->html->body->div->div
        ->addElement(
            "h2", _("Image Gallery")." - "._("Help"),
            array("class"=>"subtitle")
        );
    $dom->html->body->div->div
        ->addElement(
            "a", _("Back to gallery admin"),
            array("href"=>"http://localhost/chaton-cms/admin/index.php".
            "?tab=plugin&dir=".$_GET["dir"])
        );

    $dom->html->body->div->div->addElement("ul");
    $dom->html->body->div->div->ul
        ->addElement(
            "li",
            _("Click on your gallery name to see all the pictures it contains.")
        );
    $dom->html->body->div->div->ul
        ->addElement(
            "li",
            _(
                "To change their order, edit the numbers near them ".
                "and click on Save Order at the bottom of the page."
            )
        );
    $dom->html->body->div->div->ul
        ->addElement(
            "li",
            _(
                "Click on Edit Title/Description to edit the title ".
                "and/or the description of a picture."
            )
        );
    $dom->html->body->div->div->ul
        ->addElement("li", _("Click on Delete to delete a picture."))
        ->addElement(
            "strong",
            _(" (There is no trash, the picture will be deleted permanently.)")
        );
    $dom->html->body->div->div->ul
        ->addElement(
            "li", _("To add an image, use the form at the bottom of the page.")
        );
}
?>
