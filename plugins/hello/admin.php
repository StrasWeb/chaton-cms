<?php
/**
 * Hello world plugin admin
 * (Example plugin)
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
        ->addElement("h2", "Hello World!", array("class"=>"subtitle"));
    $dom->html->body->div->div->addElement("p", "Hello World!");
}
?>
