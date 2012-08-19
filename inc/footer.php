<?php
/**
 * Footer
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
    $dom->html->body->div->addElement(
        "footer", null, array(
            "id"=>"footer","class"=>"footer", "data-role"=>"footer"
        )
    )->addElement(
        "div", null, array(
            "data-role"=>"controlgroup", "data-type"=>"horizontal"
        )
    );
    $dom->html->body->div->footer->div->addElement("span")
        ->addElement("b")->addElement(
            "a", _("Administration"), array(
                "href"=>"admin/", "rel"=>"external",
                "data-role"=>"button", "data-icon"=>"gear"
            )
        );
    $dom->html->body->div->footer->div->addSpace();
    $dom->html->body->div->footer->div->addElement("span")
        ->addElement(
            "a", _("Powered by")." Chaton CMS v".$config->chaton_ver,
            array(
                "href"=>"http://cms.strasweb.fr/", "rel"=>"external",
                "data-role"=>"button", "data-icon"=>"info"
            )
        );
}
?>
