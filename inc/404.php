<?php
/**
 * Throws a 404 HTTP error
 *
 * PHP Version 5.3.6
 * 
 * @category Front
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
header("HTTP/1.0 404 Not Found");
$dom->html->body->div->div->section->addElement("div");
DOMenhancer_Error::$tag=$dom->html->body->div->div->section->div;
trigger_error(_("Page not found"), E_USER_ERROR);
?>
