<?php
/**
 * File that loads a plugin admin page
 *
 * PHP Version 5.3.6
 * 
 * @category Admin
 * @package  Chaton
 * @author   Pierre Rudloff <rudloff@strasweb.fr>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://cms.strasweb.fr
 */
if (isset($_GET["dir"])) {
    include_once "classes/Plugin.php";
    $plugin=new Plugin($_GET["dir"]);
    include "plugins/".$_GET["dir"]."/admin.php";
}
?>
