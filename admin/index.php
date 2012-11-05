<?php
/**
 * Admin index page
 *
 * PHP Version 5.3.6
 * 
 * @category Admin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
define("ADMIN", true);
/**
 * HTTP Digest Authentication
 * 
 * @return void
 * */
function auth ()
{
    global $nonce, $config;
    header("HTTP/1.1 401 Unauthorized");
    header(
        "WWW-Authenticate: Digest realm=\"Chaton CMS Admin (".$config->salt.")\"".
        ",qop=\"auth\",nonce=\"".$nonce.
        "\",opaque=\"".md5("Chaton CMS Admin (".$config->salt.")")."\""
    );
    header("Refresh: 0; url=../index.php");
    exit;
}

if (!isset($_COOKIE["token"])) {
    setcookie("token", md5(uniqid(rand(), true)));
}

if (is_file("../config.php")) {
    chdir("..");
    include_once "classes/Config.php";
    $config=new Config();
    include_once "inc/localization.php";
    
    $nonce = md5(
        $_SERVER["REMOTE_ADDR"].":".$config->salt.":".
        $_SERVER["HTTP_HOST"].":".date("zo")
    );

    session_start();
    if (empty($_SERVER["PHP_AUTH_DIGEST"])
        && empty($_SERVER["REDIRECT_HTTP_AUTHORIZATION"])
        && !isset($_SESSION["login"])
    ) {
        auth();
    } else {
        if (!isset($_SESSION["login"])) {
            if (empty($_SERVER["PHP_AUTH_DIGEST"])) {
                $auth=explode(", ", $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
            } else {
                $auth=explode(", ", $_SERVER["PHP_AUTH_DIGEST"]);
            }
            foreach ($auth as $value) {
                $arr=explode("=", str_replace("\"", "", $value));
                $auth[$arr[0]]=$arr[1]; 
            }
            $valid = md5(
                $config->auth.":".$nonce.":".$auth["nc"].":".
                $auth["cnonce"].":".$auth["qop"].":".
                md5(
                    $_SERVER["REQUEST_METHOD"].":".$_SERVER["REQUEST_URI"]
                )
            );
        }

        include_once "inc/version.php";

        if ((isset($auth)
            && $auth["opaque"]==md5("Chaton CMS Admin (".$config->salt.")")
            && $auth["response"]==$valid)
            || (isset($_SESSION["login"]) && $_SESSION["login"])
        ) {
            include_once "classes/dom-enhancer/XMLDocument.php";
            $doc=new DOMenhancer_XMLDocument();
            $dom=$doc->DOM;            
            $dom->html->head->addElement("meta", null, array("charset"=>"UTF-8"));
            $dom->html->head->addElement(
                "title", "Chaton CMS - "._("Administration")
            );
            $dom->html->head->addElement(
                "link", null, array("rel"=>"stylesheet", "href"=>"admin_style.css")
            );
            $dom->html->head->addElement(
                "link", null, array("rel"=>"author", "href"=>"http://strasweb.fr/")
            );
            $dom->html->head->addElement(
                "link", null, array(
                    "rel"=>"license",
                    "href"=>"http://www.gnu.org/licenses/gpl-3.0.html"
                )
            );
            if (isset($config->lang) && is_file("locale/".$config->lang.".po")) {
                $dom->html->head->addElement(
                    "link", null, array(
                        "rel"=>"gettext", "href"=>"../locale/".$config->lang.".po",
                        "type"=>"application/x-po"
                    )
                );
            }

            $beta=$config->pre_ver?"_beta":"";
            $dom->html->addElement("body")->addElement(
                "div", null, array("class"=>"header")
            )->addElement(
                "img", null, array("alt"=>"Chaton CMS v.".$config->chaton_ver,
                "src"=>"img/logo_chaton.png", "class"=>"logo")
            );
            $dom->html->body->div->addElement(
                "h1", null, array("class"=>"title")
            )->addElement("span", $config->title, array("class"=>"sitename"));
            $dom->html->body->div->h1->addElement("br");
            $dom->html->body->div->h1->addElement("span", _("Administration"));
            $dom->html->body->div->addElement(
                "div", null, array("class"=>"front")
            )->addElement(
                "a", _("Go to website"), array("href"=>"../index.php")
            );
            $dom->html->body->div->addElement(
                "div", null, array("class"=>"clear")
            );
            $dom->html->body->addElement(
                "div", null, array("class"=>"wrapper")
            )->addElement("ul", null, array("class"=>"menu"));    
            $dom->html->body->div->ul->addElement(
                "li", null, array("class"=>"small hidden")
            )->addElement(
                "a", _("Return to website"), array("href"=>"../index.php")
            );
            $dom->html->body->div->ul->addElement("li")
                ->addElement("hr", null, array("class"=>"hidden"));
            $menu=array("main"=>_("Main"),
            "news"=>_("News"), "categories"=>_("Categories"),
            "pages"=>_("Pages"), "links"=>_("External Links"),
            "themes"=>_("Themes"), "plugins"=>_("Plugins"));
            foreach ($menu as $item=>$name) {
                $dom->html->body->div->ul->addElement("li")->addElement(
                    "a", $name, array("href"=>"index.php?tab=".$item)
                );
            }
            if ($config->multilingual) {
                $dom->html->body->div->ul->addElement("li")->addElement(
                    "a", _("Languages"), array("href"=>"index.php?tab=lang")
                );
            }
            $maindiv=$dom->html->body->div->addElement(
                "div", null, array("class"=>"main")
            );
            if (isset($_GET["tab"])) {
                $tab=$_GET["tab"];
            } else {
                $tab="main";    
            }
            DOMenhancer_Error::$tag=$dom->html->body->div->div;
            include $tab.".php";
            print($dom->saveHTML());
        } else {
            //var_dump($_SERVER["PHP_AUTH_DIGEST"]);
            auth();
        }
    }
}
?>
