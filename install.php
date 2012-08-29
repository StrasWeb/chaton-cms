<?php
/**
 * Install script
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
require_once __DIR__."/classes/Config.php";
$config = new Config();
require __DIR__."/inc/localization.php";
$domimpl=new DOMImplementation();
$doctype=$domimpl->createDocumentType('html');
$dom=$domimpl->createDocument("http://www.w3.org/1999/xhtml", "html", $doctype);
require_once __DIR__."/classes/dom-enhancer/XMLDocument.php";
$doc=new DOMenhancer_XMLDocument();
$dom=$doc->DOM;
//$dom->registerNodeClass('DOMElement', 'NewDOMElement');
//$dom->html=$dom->documentElement;

$dom->html->addElement("head")->addElement("meta", null, array("charset"=>"UTF-8"));
$dom->html->head->addElement("title", "Chaton CMS - "._("Install"));
$dom->html->head->addElement(
    "link", null, array("rel"=>"stylesheet", "href"=>"inc/install.css")
);
$dom->html->addElement("body");
if (isset($_POST['install'])) {
    include "inc/version.php";
    if (isset($_POST['sql_host'])) {
        $config->host=$_POST['sql_host'];
        $config->user=$_POST['sql_user'];
        $config->db=$_POST['sql_db'];
        $config->password=$_POST['sql_password'];
        $config->prefix=empty($_POST['sql_prefix'])?"chaton_":$_POST['sql_prefix'];
    } else {
        $config->host="localhost";
        $config->user="";
        $config->db="";
        $config->password="";
        $config->prefix="";
    }
    if ($_POST['install']=="sql") {
        $dom->html->body->addElement(
            "form", null, array("action"=>"install.php", "method"=>"post")
        )->addElement("h2", _("Database config"));
        $dom->html->body->form->addElement(
            "label", _("Host:")." ", array("for"=>"sql_host")
        );
        $dom->html->body->form->addElement(
            "input", null, array("type"=>"text", "id"=>"sql_host",
            "name"=>"sql_host", "value"=>$config->host)
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "label", _("Database:")." ", array("for"=>"sql_db")
        );
        $dom->html->body->form->addElement(
            "input", null, array("type"=>"text", "id"=>"sql_db",
            "name"=>"sql_db", "value"=>$config->db)
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "label", _("Username:")." ", array("for"=>"sql_user")
        );
        $dom->html->body->form->addElement(
            "input", null, array(
                "type"=>"text", "id"=>"sql_user",
                "name"=>"sql_user", "value"=>$config->user
            )
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "label", _("Password:")." ", array("for"=>"sql_password")
        );
        $dom->html->body->form->addElement(
            "input", null, array(
                "type"=>"password", "id"=>"sql_password",
                "name"=>"sql_password", "value"=>$config->password
            )
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "label", _("Prefix:")." ", array("for"=>"sql_prefix")
        );
        $dom->html->body->form->addElement(
            "input", null, array(
                "type"=>"text", "id"=>"sql_prefix", "name"=>"sql_prefix",
                "value"=>$config->prefix, "placeholder"=>"chaton_"
            )
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "input", null, array(
                "type"=>"hidden", "id"=>"sql_password",
                "name"=>"install","value"=>"sql_valid"
            )
        );
        $dom->html->body->form->addElement(
            "input", null, array(
                "type"=>"submit", "value"=>_("Next")
            )
        );
    } elseif ($_POST['install']=="sql_valid") {
        $config->connectDB();
        if ($config->sql) {
            $query=file_get_contents("sql/getConfig.sql"); 
            $query=sprintf($query, $config->prefix."main");     
            if ($query) {
                $query = $config->sql->prepare($query);
                $result=$query->execute();   
            }
            if ($result) {
                $dom->html->body->addElement(
                    "form",
                    _("Error: This database already contains a Chaton install !"),
                    array("action"=>"install.php", "method"=>"post")
                );
                $dom->html->body->form->addElement("br");
                $dom->html->body->form->addElement(
                    "span", _("Please empty it or choose another database.")
                );
                $dom->html->body->form->addElement("br");
                $dom->html->body->form->addElement("br");
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"hidden", "name"=>"sql_host",
                        "value"=>$config->host
                    )
                );
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"hidden", "name"=>"sql_db",
                        "value"=>$config->db
                    )
                );
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"hidden", "name"=>"sql_user",
                        "value"=>$config->user
                    )
                );
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"hidden", "name"=>"sql_password",
                        "value"=>$config->password
                    )
                );
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"hidden", "name"=>"sql_prefix",
                        "value"=>$config->prefix
                    )
                );
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"hidden", "name"=>"install", "value"=>"sql"
                    )
                );
                $dom->html->body->form->addElement(
                    "input", null, array(
                        "type"=>"submit", "value"=>_("Click here to try again")
                    )
                );
            } else {
                if (is_writable("./")) {
                    $fp=fopen("config.php", "w");
                    $content="<?php if (isset(\$this)) { \$this->host='".
                    $config->host."'; \$this->db='".$config->db."'; \$this->user='".
                    $config->user."'; \$this->password='".$config->password.
                    "'; \$this->prefix='".$config->prefix."'; }?>";
                    fwrite($fp, $content);
                    fclose($fp);
                    $dom->html->body->addElement(
                        "form", _("Connection OK"), array(
                            "action"=>"install.php", "method"=>"post"
                        )
                    );
                    $dom->html->body->form->addElement("br");
                    $dom->html->body->form->addElement("br");
                    $dom->html->body->form->addElement(
                        "input", null, array("type"=>"hidden",
                        "name"=>"sql_host", "value"=>$config->host)
                    );
                    $dom->html->body->form->addElement(
                        "input", null, array(
                            "type"=>"hidden", "name"=>"sql_db",
                            "value"=>$config->db
                        )
                    );
                    $dom->html->body->form->addElement(
                        "input", null, array(
                            "type"=>"hidden", "name"=>"sql_user",
                            "value"=>$config->user
                        )
                    );
                    $dom->html->body->form->addElement(
                        "input", null, array(
                            "type"=>"hidden", "name"=>"sql_password",
                            "value"=>$config->password
                        )
                    );
                    $dom->html->body->form->addElement(
                        "input", null, array(
                            "type"=>"hidden", "name"=>"sql_prefix",
                            "value"=>$config->prefix
                        )
                    );
                    $dom->html->body->form->addElement(
                        "input", null, array(
                            "type"=>"hidden", "name"=>"install",
                            "value"=>"create_tables"
                        )
                    );
                    $dom->html->body->form->addElement(
                        "input", null, array(
                            "type"=>"submit", "value"=>_("Click here to continue")
                        )
                    );
                } else {
                    trigger_error(
                        _(
                            "Chaton's directory is not writable.
                            Please check your permissions."
                        ),
                        E_USER_ERROR
                    );
                }
            }
        } else {
            $dom->html->body->addElement(
                "form", _("Error: Unable to connect to the database!"),
                array("action"=>"install.php", "method"=>"post")
            );
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement(
                "span", _("Please check your parameters.")
            );
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"sql_host", "value"=>$config->host
                )
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"sql_db", "value"=>$config->db
                )
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"sql_user",
                    "value"=>$config->user
                )
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"sql_password",
                    "value"=>$config->password
                )
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"install", "value"=>"sql"
                )
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"submit",
                    "value"=>_("Click here to try again")
                )
            );
        }
    } elseif ($_POST['install']=="create_tables") {
        $query=file_get_contents("sql/install.sql");     
        $query =sprintf(
            $query, $config->prefix."links",  $config->prefix."main",
            $config->prefix."news", $config->prefix."pages",
            $config->prefix."plugins",  $config->prefix."param",
            $config->prefix."categories"
        ); 
        if ($query) {
            $query = $config->sql->prepare($query);
            $result=$query->execute();   
        }
        if ($result) {
            $dom->html->body->addElement(
                "form", _("Database structure successfully created"),
                array("action"=>"install.php", "method"=>"post")
            );
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_host",
                "value"=>$config->host)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_db",
                "value"=>$config->db)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_user",
                "value"=>$config->user)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_password",
                "value"=>$config->password)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_prefix",
                "value"=>$config->prefix)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"install",
                "value"=>"site_info")
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"submit",
                "value"=>_("Click here to continue"))
            );
        } else {
            $dom->html->body->addElement(
                "form", _("Error: Unable to create the tables!"),
                array("action"=>"install.php", "method"=>"post")
            );
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement(
                "span", _("Please check your parameters.")
            );
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_host",
                "value"=>$config->host)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_db",
                "value"=>$config->db)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_user",
                "value"=>$config->user)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_password",
                "value"=>$config->password)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_prefix",
                "value"=>$config->prefix)
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"hidden", "name"=>"install", "value"=>"sql"
                )
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"submit",
                "value"=>_("Click here to try again"))
            );
        }
    } elseif ($_POST['install']=="site_info") {
        $dom->html->body->addElement(
            "form", null, array("action"=>"install.php", "method"=>"post")
        )->addElement("h2", _("Website config"));
        $dom->html->body->form->addElement(
            "label", _("Title:")." ", array("for"=>"title")
        );
        $dom->html->body->form->addElement(
            "input", null, array("type"=>"text", "id"=>"title",
            "name"=>"title", "value"=>"")
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "label", _("Username:")." ", array("for"=>"user")
        );
        $dom->html->body->form->addElement(
            "input", null, array("type"=>"text", "id"=>"user",
            "name"=>"user", "value"=>"")
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "label", _("Password:")." ", array("for"=>"password")
        );
        $dom->html->body->form->addElement(
            "input", null, array("type"=>"password", "id"=>"password",
            "name"=>"password", "value"=>"")
        );
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement("br");
        $dom->html->body->form->addElement(
            "input", null, array("type"=>"checkbox", "id"=>"multiling",
            "name"=>"multiling", "value"=>"")
        );
        $dom->html->body->form->addElement(
            "label", _("Enable multilingual mode (experimental)")." ",
            array("for"=>"multiling", "class"=>"auto")
        );

        $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement("br");
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_host",
                "value"=>$config->host)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_db",
                "value"=>$config->db)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_user",
                "value"=>$config->user)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_password",
                "value"=>$config->password)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"sql_prefix",
                "value"=>$config->prefix)
            );
            $dom->html->body->form->addElement(
                "input", null, array("type"=>"hidden", "name"=>"install",
                "value"=>"site_info_add")
            );
            $dom->html->body->form->addElement(
                "input", null, array(
                    "type"=>"submit", "value"=>_("Click here to continue")
                )
            );
    } else if ($_POST['install']=="site_info_add") {
        $multiling=isset($_POST['multiling'])?1:0;

        if ($config->add(
            $_POST['title'], $_POST['user'], $_POST['password'],
            $multiling, $config->chaton_shortver
        )) {
            $dom->html->body->addElement(
                "form", _("Installation successful")
            )->addElement("br");
            $dom->html->body->form->addElement("span", _("You can now")." ")
                ->addElement(
                    "a", _("return to your website."), array("href"=>"index.php")
                );
        }
    }
} else {
    if (is_file("config.php")) {
        die(_("Chaton CMS is already installed."));
    }
    $dom->html->body->addElement(
        "form", _("You need to install Chaton CMS."),
        array("action"=>"install.php", "method"=>"post")
    );
    $dom->html->body->form->addElement("br");
    $dom->html->body->form->addElement("br");
    $dom->html->body->form->addElement(
        "input", null, array("type"=>"hidden", "name"=>"install", "value"=>"sql")
    );
    $dom->html->body->form->addElement(
        "input", null, array(
            "type"=>"submit", "value"=>_("Click here to install")
        )
    );
}
print($dom->saveHTML());
?>
