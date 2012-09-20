<?php
/**
 * Custom footer plugin hook
 *
 * PHP Version 5.3.6
 * 
 * @category Plugin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($this)) {
    global $config, $pages;
    include_once "plugins/".$this->dir."/Footer.php";
    $plugin=new Footer($this->dir);
    global $dom;
    $footer=$dom->getElementById("footer");
    if (!$plugin->default) {
        $footer->removeChild($footer->childNodes->item(0));
    }
    if (!empty($plugin->content)) {
        $footer->content=$dom->createDocumentFragment();
        $footer->content->appendXML(stripslashes($plugin->content));
        $footer->appendChild($footer->content);
    }
}
?>
