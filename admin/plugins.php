<?php
/**
 * Admin page with list of plugins
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
    include_once "classes/Plugin.php" ;
    $dom->html->body->div->div->addElement(
        "h2", _("Plugins"), array("class"=>"subtitle")
    );
    if (isset($_POST['modified']) && $_POST["token"] == $_COOKIE["token"]) {
        $plugins=Plugin::getList();
        foreach ($plugins as $dir) {
            $plugin=new Plugin($dir);
            if (isset($_POST[$dir])) {
                $plugin->enable() or die;
            } else {
                $plugin->disable() or die; 
            }
        }
        $dom->html->body->div->div->addElement(
            "div", _("Modifications succesfully saved"),
            array("class"=>"modified")
        );   
    }
    $dom->html->body->div->div->addElement(
        "div", null, array("id"=>"articles")
    )->addElement(
        "div", _("Choose the plugins to enable:"), array("id"=>"header")
    );
    $dom->html->body->div->div->div->addElement(
        "form", null, array(
            "action"=>"index.php?tab=plugins", "method"=>"post"
        )
    );
    $plugins=Plugin::getList();
    foreach ($plugins as $dir) {
        $plugin=new Plugin($dir);
        $dom->html->body->div->div->div->form->addElement(
            "input", null, array(
                "type"=>"checkbox", "id"=>$dir, "name"=>$dir
            )
        );
        $dom->html->body->div->div->div->form->addElement(
            "label", $plugin->name."  ",
            array("class"=>"plugin_name", "for"=>$dir)
        );
        if ($plugin->enabled) {
            $dom->html->body->div->div->div->form->input->setAttribute(
                "checked", true
            );
        }
        if ((isset($plugin->minver)
            && $config->chaton_shortver < $plugin->minver)
            || (isset($plugin->maxver)
            && $config->chaton_shortver > $plugin->maxver)
        ) {
            $plugin->admin=false;
            $dom->html->body->div->div->div->form->input->setAttribute(
                "disabled", true
            );
        }
        if (isset($plugin->admin) && $plugin->admin && $plugin->enabled) {
            if (isset($config->multiling) && $config->multiling) {
                $dom->html->body->div->div->div->form->addElement(
                    "span", _("Settings:")." ",
                    array("class"=>"plugin_setting")
                );
                foreach ($config->languages as $lang=>$value) {
                    $dom->html->body->div->div->div->form->addElement(
                        "a", $lang, array("class"=>"plugin_setting",
                        "href"=>"index.php?tab=plugin&dir=".$dir."&lang=".$lang)
                    );
                    $dom->html->body->div->div->div->form->addElement(
                        "span", " "
                    );
                }
            } else {
                $dom->html->body->div->div->div->form->addElement(
                    "a", null, array("class"=>"plugin_setting",
                    "href"=>"index.php?tab=plugin&dir=".$dir, "title"=>_("settings"))
                )->addElement(
                    "img", null, array("height"=>18, "src"=>"img/cog.svg",
                    "alt"=>_("Settings"), "class"=>"cog")
                );
            }
        }
        $dom->html->body->div->div->div->form->addElement("br");
    }
    $dom->html->body->div->div->div->form->addElement("br");
    $dom->html->body->div->div->div->form->addElement("br");
    $dom->html->body->div->div->div->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"modified",
        "value"=>true)
    );
    $dom->html->body->div->div->div->form->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"token", "value"=>$_COOKIE["token"]
        )
    );
    $dom->html->body->div->div->div->form->addElement(
        "input", null, array("type"=>"submit", "value"=>_("Save"))
    );
}
?>
