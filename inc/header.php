<?php
/**
 * Header
 * (logo and menus)
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($dom)) {
    $dom->html->body->div->addElement("div", null, array("id"=>"headerwrapper"));
    $dom->html->body->div->div->addElement(
        "header", null, array(
            "id"=>"header","class"=>"mainheader", "data-role"=>"header"
        )
    );
    $dom->html->body->div->div->header->addElement(
        "h1", null, array("id"=>"title","class"=>"title")
    )->addElement(
        "a", null,
        array(
            "title"=>_("Home"),"href"=>"index.php?lang=".$config->lang
        )
    );
    if ($config->logo) {
        $dom->html->body->div->div->header->h1->a->addElement(
            "img", null, array("src"=>$config->logo, "alt"=>$config->title,
            "style"=>"max-width:100%;")
        );
    } else {
        $dom->html->body->div->div->header->h1->a
            ->nodeValue=htmlspecialchars($config->title);
    }
    $dom->html->body->div->div->addElement(
        "div", null, array("id"=>"menu_wrapper","class"=>"menu_wrapper")
    );
    $dom->html->body->div->div->div->addElement(
        "nav", null,
        array(
            "id"=>"menu","class"=>"menu", "data-role"=>"navbar"
        )
    );
    $dom->html->body->div->div->div->nav->addElement(
        "div", null, array("id"=>"search", "class"=>"search")
    )->addElement(
        "form", null, array("action"=>"search.php","method"=>"get")
    )->addElement("label", _("Search:")." ", array("for"=>"search_field"));
    $dom->html->body->div->div->div->nav->div->form->addElement(
        "input", null, array(
            "type"=>"search", "name"=>"search_field",
            "id"=>"search_field"
        )
    );
    $dom->html->body->div->div->div->nav->div->form->addElement(
        "input", null, array(
            "type"=>"submit", "value"=>_("OK"), "id"=>"ok_search",
            "class"=>"ok_search"
        )
    );
    
    $navbar=$dom->html->body->div->div->div->nav->addElement("div");
    if ($config->homepage!="news") {
        include_once "classes/Page.php";
        $navbar->page=new Page($config->lang, $config->homepage);
        $navbar->addElement(
            "ul", null, array("id"=>"homepageMenu")
        )->addElement(
            "li", null, array("id"=>"homepageMenuItem")
        )->addElement(
            "a", $navbar->page->title,
            array("href"=>"index.php?lang=".$config->lang)
        );
    }
    $navbar->addElement(
        "ul", null, array("id"=>"newsMenu", "class"=>"newsMenu")
    )->addElement(
        "li", null, array("id"=>"newsMenuItem")
    )->addElement(
        "a", _($config->news_title),
        array("href"=>"index.php?page=news&lang=".$config->lang)
    );
    if (isset($_GET["page"])&&$_GET["page"]=="news") {
        $navbar->ul->li->setAttribute("class", "current");
    }
    include_once "classes/Page.php";
    $pages=Page::getList($config->lang);
    if (count($pages)>0) {
        $navbar->addElement(
            "ul", null, array("id"=>"pageMenu")
        );
        foreach ($pages as $item) {
            $myPage = new Page($config->lang, $item->id);
            if ($item->id!=$config->homepage && $myPage->show) {
                $navbar->ul->addElement("li")->addElement(
                    "a", $item->title, array(
                        "href"=>"index.php?page=".$item->id."&lang=".$config->lang
                    )
                );
                if (isset($_GET["page"]) && $item->id==$_GET["page"]) {
                    $navbar->ul->li->setAttribute("class", "current");
                    $navbar->ul->li->a->removeAttribute("href");
                }
            }
        }
    }
    include_once "classes/Link.php";
    $links=Link::getList();
    if (count($links)>0) {
        $navbar->addElement("ul");
        foreach ($links as $link) {
            $navbar->ul->addElement("li")
                ->addElement(
                    "a", $link->title, array(
                        "href"=>$link->url, "rel"=>"external"
                    )
                );
        }
    }
    $dom->html->body->div->div->div->nav->addElement(
        "ul", null, array("id"=>"pluginMenu")
    );
    if ($config->multilingual&&!empty($config->languages)) {
        $dom->html->body->div->div->div->nav->addElement(
            "div", null, array("id"=>"chooselang")
        )->addElement(
            "form", null, array(
                "action"=>"index.php", "method"=>"get", "data-role"=>"fieldcontain"
            )
        )->addElement(
            "select", null, array(
                "name"=>"lang", "data-mini"=>"true", "data-inline"=>"true"
            )
        );
        foreach ($config->languages as $key=>$value) {
            $dom->html->body->div->div->div->nav->div->form->select->addElement(
                "option", $languageCodes[$key], array("value"=>$key)
            );
            if ($config->lang==$key) {
                $dom->html->body->div->div->div->nav->div->form->select
                    ->option->setAttribute("selected", true);
            }
        }
        $dom->html->body->div->div->div->nav->div->form->addElement(
            "input", null, array(
                "type"=>"submit","value"=>_("OK"), "data-mini"=>"true",
                "data-inline"=>"true"
                )
        );
    }
    $dom->html->body->div->addElement(
        "div", null, array("id"=>"mainwrapper", "class"=>"mainwrapper",
        "data-role"=>"content")
    );
}
?>
