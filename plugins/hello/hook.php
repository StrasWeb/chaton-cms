<?php
/**
 * Hello world plugin hook
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
global $dom;
if (isset($dom)) {
    $header=$dom->getElementById("header");
    $header->addElement("p", "Hello World!");
}
?>
