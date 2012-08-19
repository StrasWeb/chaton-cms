<?php
/**
 * BrowserID plugin admin page
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
    include_once __DIR__."/BrowserID.php";
    $plugin=new BrowserID();
    if (isset($_POST['email'])) {
        $plugin->email=$_POST['email'];
        if ($plugin->setParam("email", $plugin->email)) {
            $dom->html->body->div->div->addElement(
                "div", _("Modifications succesfully saved"),
                array("class"=>"modified")
            );
        }
    }
    $dom->html->body->div->div->addElement(
        "h2", "BrowserID", array("class"=>"subtitle")
    );
    $dom->html->body->div->div->addElement(
        "form", null, array(
            "action"=>"index.php?tab=plugin&dir=".$_GET["dir"], "method"=>"post"
        )
    )->addElement("label", _("Admin e-mail:"), array("for"=>"title"));
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"text", "id"=>"email",
        "name"=>"email", "value"=>$plugin->email)
    );
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"submit")
    );
}
?>
