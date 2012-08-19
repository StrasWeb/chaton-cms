<?php
/**
 * Main admin page
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
        "h2", _("Main"), array("class"=>"subtitle")
    );
    if (isset($_POST["modified"]) && $_POST["token"] == $_COOKIE["token"]) {
        if (empty($_POST["site_title"])) {
            trigger_error(_("You must enter a title"), E_USER_WARNING);
        } else {
            $config->title=stripslashes($_POST["site_title"]);
            $config->desc=empty(
                $_POST["site_desc"]
            )?"":stripslashes($_POST["site_desc"]);
            $config->lang=empty(
                $_POST["def_lang"]
            )?"":stripslashes($_POST["def_lang"]);
            $config->homepage=stripslashes($_POST["homepage"]);
            $config->logo=empty(
                $_POST["site_logo"]
            )?"":stripslashes($_POST["site_logo"]);
            $config->news_title=empty(
                $_POST["news_title"]
            )?"":stripslashes($_POST["news_title"]);
            $config->update();
            trigger_error(_("Modifications succesfully saved"), E_USER_NOTICE);
        }
    }
    $dom->html->body->div->div->addElement(
        "form", null, array("action"=>"", "method"=>"post")
    )->addElement("div")->addElement(
        "label", _("Website name:"),
        array("for"=>"site_title",
        "title"=>_("Displayed in the header and title bar"))
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array("type"=>"text", "id"=>"site_title",
        "name"=>"site_title", "value"=>$config->title, "required"=>true)
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "label", _("Website description:"),
        array("for"=>"site_desc",
        "title"=>_("Displayed in search engines results"))
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array(
            "type"=>"text", "id"=>"site_desc", "name"=>"site_desc",
            "value"=>$config->desc
        )
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "label", _("Logo (URL):"), array(
            "for"=>"site_logo", "title"=>_("Adress of the image to use")
        )
    );
    $dom->html->body->div->div->form->div->addElement("br");
    //Il faudrait peut être vérifier si l'URL est valide
    //Il faudrait aussi pouvoir uploader une image
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array("type"=>"url", "id"=>"site_logo",
        "name"=>"site_logo", "value"=>$config->logo)
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "label", _("News page title:"), array("for"=>"site_desc")
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array("type"=>"text", "id"=>"news_title",
        "name"=>"news_title", "value"=>$config->news_title)
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "label", _("Default language:"),
        array("for"=>"def_lang",
        "title"=>_("Used to display the interface."))
    );
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "select", null, array("id"=>"def_lang", "name"=>"def_lang")
    );
    if ($config->multilingual) {
        if (empty($config->languages)) {
                $dom->html->body->div->div->form->div->select->addElement(
                    "option", $languageCodes["en"],
                    array("value"=>"en"), array("selected"=>true)
                );
        } else {
            foreach ($config->languages as $key=>$value) {
                $dom->html->body->div->div->form->div->select->addElement(
                    "option", $languageCodes[$key], array("value"=>$key)
                );
                if ($key==$config->lang) {
                    $dom->html->body->div->div->form->div->select->option
                        ->setAttribute("selected", true);
                }    
            }
        }
    } else {
        foreach ($languageCodes as $key=>$value) {
            $dom->html->body->div->div->form->div->select->addElement(
                "option", $value, array("value"=>$key)
            );
            if ($key==$config->lang) {
                $dom->html->body->div->div->form->div->select->option
                    ->setAttribute("selected", true);
            }
        }
    }
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "label", _("Home page:"), array(
            "for"=>"homepage",
            "title"=>_(
                "First page displayed on the site or when you click the banner"
            )
        )
    );
    $dom->html->body->div->div->form->div->addElement("br");
    include_once "classes/Page.php";
    $dom->html->body->div->div->form->div->addElement(
        "select", null, array("id"=>"homepage", "name"=>"homepage")
    );
    $dom->html->body->div->div->form->div->select->addElement(
        "option", _("News"), array("value"=>"news")
    );
    $pages=Page::getList($config->lang);
    foreach ($pages as $page) {
        $dom->html->body->div->div->form->div->select->addElement(
            "option", $page->title, array("value"=>$page->id)
        );
        if ($page->id==$config->homepage) {
            $dom->html->body->div->div->form->div->select->option
                ->setAttribute("selected", true);
        }
    }
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement("br");
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"modified", "value"=>true
        )
    );
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array(
            "type"=>"hidden", "name"=>"token", "value"=>$_COOKIE["token"]
        )
    );
    $dom->html->body->div->div->form->div->addElement(
        "input", null, array("type"=>"submit", "value"=>_("Save"))
    );
}
?>
