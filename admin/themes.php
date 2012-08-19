<?php
/**
 * Admin page to select theme
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
        "h2", _("Themes"), array("class"=>"subtitle")
    );
    if (isset($_POST['modified']) && $_POST["token"] == $_COOKIE["token"]) {
        $config->setTheme($_POST['site_theme']);
        trigger_error(_("Modifications succesfully saved"), E_USER_NOTICE);
    }
    $dom->html->body->div->div->addElement(
        "form", null, array("action"=>"", "method"=>"post")
    )->addElement("div")->addElement(
        "label", _("Select the theme to use:")." ",
        array("for"=>"site_theme")
    );
    $dom->html->body->div->div->form->div->addElement(
        "select", null, array("id"=>"site_theme", "name"=>"site_theme")
    )->addElement(
        "option", _("none"), array("value"=>"")
    );
    if ($handle=opendir('themes')) {
        while (false!==($dir=readdir($handle))) {
            /*On pourrait utiliser is_dir plutôt que !is_file
             * mais ça a l'air de déconner en local sur Mac...*/
            if ($dir!="."
                && $dir!=".." 
                && $dir!=".svn"
                && $dir!=".DS_Store"
                && !is_file($dir)
            ) {
                $dom->html->body->div->div->form->div->select->addElement(
                    "option", $dir
                );
                if ($dir==$config->theme) {
                    $dom->html->body->div->div->form->div
                        ->select->option->setAttribute(
                            "selected", true
                        );
                }
            }
        }
        closedir($handle);
    }
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement("br");
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"modified", "value"=>true)
    );
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"token", "value"=>$_COOKIE["token"]
        )
    );
    $dom->html->body->div->div->form->addElement(
        "input", null, array("type"=>"submit", "value"=>_("Save"))
    );
}
?>
