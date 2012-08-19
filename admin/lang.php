<?php
/**
 * Admin page to choose languages available on the site
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
        "h2", _("Languages"), array("class"=>"subtitle")
    );
    if (isset($_POST['modified'])) {
        $config->languages=array();
        foreach ($languageCodes as $key=>$value) {
            if (isset($_POST[$key])) {
                $config->languages[$key]=true;
            }
        }  
        if ($config->updateLanguages()) {
            $dom->html->body->div->div->addElement(
                "div", _("Modifications succesfully saved"),
                array("class"=>"modified")
            );
        }
    }
    $dom->html->body->div->div->addElement(
        "form", null, array("action"=>"index.php?tab=lang", "method"=>"post")
    );
    foreach ($languageCodes as $key=>$value) {
        $dom->html->body->div->div->form->addElement(
            "input", null, array(
                "value"=>$key, "id"=>$key, "name"=>$key, "type"=>"checkbox"
            )
        );
        $dom->html->body->div->div->form->addElement(
            "label", $value, array("for"=>$key)
        );
        $dom->html->body->div->div->form->addElement("br");
        if ($key==$config->lang || isset($config->languages[$key])) {
            $dom->html->body->div->div->form->input->setAttribute("checked", true);
        }
    }
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"modified", "value"=>true)
    );
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"submit", "value"=>_("Save"))
    );
}
?>
